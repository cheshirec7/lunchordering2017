<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Repositories\AccountRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\ExpressCheckout;
use Yajra\DataTables\Facades\DataTables;

class MyAccountController extends Controller
{
    protected $accounts;
    protected $users;
    protected $orders;
    protected $payments;

    /**
     * @param AccountRepository $accounts
     * @param UserRepository $users
     * @param OrderRepository $orders
     * @param PaymentRepository $payments
     */
    public function __construct(AccountRepository $accounts,
                                UserRepository $users,
                                OrderRepository $orders,
                                PaymentRepository $payments)
    {
        $this->accounts = $accounts;
        $this->users = $users;
        $this->orders = $orders;
        $this->payments = $payments;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //order_count,total_price,first_name,last_name,user_id
        $order_aggs = $this->orders->myAccountAggregates(Auth::id());
        //payment_count, credit_amt, fees
        $payment_agg = $this->payments->myAccountAggregate(Auth::id());
        $cur_balance = $this->accounts->currentBalance(Auth::id());

        $trx_fee = '';
        $total_due = '';
        if ($cur_balance < 0) {
            $trx_fee = round(-$cur_balance * config('app.paypay_pct')) + config('app.paypay_fee');
            $total_due = -$cur_balance + $trx_fee;
        }

        $users = $this->users->getForSelect(Auth::id());
        return view('myaccount.index', [
            'order_aggs' => $order_aggs,
            'payment_agg' => $payment_agg,
            'cur_balance' => $cur_balance,
            'trx_fee' => $trx_fee,
            'total_due' => $total_due,
            'users' => $users
        ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getPaymentsDatatable()
    {
        return DataTables::of($this->payments->getForMyAccountPaymentsDatatable())
            ->escapeColumns(['credit_desc'])
            ->editColumn('credit_amt', function ($payment) {
                return money_format('$%.2n', $payment->credit_amt / 100);
            })
            ->editColumn('credit_date', function ($payment) {
                return $payment->credit_date->toDateString();
            })
            ->editColumn('pay_method', function ($payment) {
                switch ($payment->pay_method) {
                    case 1:
                        return 'Cash';
                        break;
                    case 2:
                        return 'Check';
                        break;
                    case 3:
                        return 'PayPal';
                        break;
                    case 4:
                        return 'Adjustment';
                        break;
                    default:
                        return 'Undefined';
                        break;
                }
            })
            ->make(true);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getOrdersDatatable(Request $request)
    {
        return DataTables::of($this->orders->datatableForUserID($request->input('uid')))
            ->escapeColumns(['description'])
            ->editColumn('total_price', function ($order) {
                return money_format('$%.2n', $order->total_price / 100);
            })
            ->editColumn('order_date', function ($order) {
                return $order->order_date->toDateString();
            })
            ->make(true);
    }

    /**
     * @return mixed
     */
    public function cancel()
    {
        return redirect()
            ->route('myaccount')
            ->withFlashInfo('PayPal payment cancelled.');
    }

    /**
     * @return mixed
     */
    public function pay(Request $request)
    {
        $total = $request->input('total_due', 0) / 100;
//        $fee = $request->input('fee', 0) / 100;

        if ($total <= 0)
            return redirect()->back()->withFlashInfo('Payment amount must be greater than zero.');

        $provider = new ExpressCheckout;

        $data = [];
        $data['items'] = [
            [
                'name' => 'CCA Lunch Payment',
                'price' => $total,
                'qty' => 1
            ]
        ];
        $data['total'] = $total;
        $data['invoice_id'] = Carbon::now()->timestamp;
        $data['invoice_description'] = 'CCA Lunch Payment for ' . Carbon::today()->toDateString();
        $data['return_url'] = route('myaccount.completepayment');
        $data['cancel_url'] = route('myaccount.cancelpayment');

        $response = $provider->setExpressCheckout($data);

        if ($response['ACK'] == config('app.PAYPAL_ACK_SUCCESS') ||
            $response['ACK'] == config('app.PAYPAL_ACK_SUCCESS_WITH_WARNING')) {
            return redirect($response['paypal_link']);
        }

        Log::error($response);
        return redirect()
            ->route('myaccount')
            ->withFlashDanger('Error: ' . $response['L_SHORTMESSAGE0']);
    }


    public function completePayment(Request $request)
    {
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request['token']);
        if ($response['ACK'] == config('app.PAYPAL_ACK_SUCCESS') ||
            $response['ACK'] == config('app.PAYPAL_ACK_SUCCESS_WITH_WARNING')) {

            $data = [];
            $data['items'] = [
                [
                    'name' => $response['L_NAME0'],
                    'price' => $response['L_AMT0'],
                    'qty' => 1
                ]
            ];

            $data['total'] = $response['L_PAYMENTREQUEST_0_AMT0'];
            $data['invoice_id'] = $response['PAYMENTREQUEST_0_INVNUM'];
            $data['invoice_description'] = $response['PAYMENTREQUEST_0_DESC'];

            $response = $provider->doExpressCheckoutPayment($data, $response['TOKEN'], $response['PAYERID']);

            $payment = new Payment();
            $payment->account_id = Auth::id();
            $payment->pay_method = config('app.pay_method_paypal');
            $payment->credit_desc = 'Trx ID: ' . $response['PAYMENTINFO_0_TRANSACTIONID'];
            $payment->credit_date = Carbon::now();
            $payment->credit_amt = $response['PAYMENTINFO_0_AMT'] * 100;
            $payment->fee = $response['PAYMENTINFO_0_FEEAMT'] * 100;
            $payment->save();

            $this->accounts->updateAccountAggregates(Auth::id());

            return redirect()
                ->route('myaccount')
                ->withFlashSuccess('A payment for $' . $response['PAYMENTINFO_0_AMT'] . ' was successfully applied to your account.');

        }

        Log::error($response);
        return redirect()
            ->route('myaccount')
            ->withFlashDanger('Error: ' . $response['L_SHORTMESSAGE0']);
    }

    /**
     * @return mixed
     */
    public function notify(Request $request)
    {
        Log::info('Paypal notify');
        Log::info($request);
//        return redirect()
//            ->route('myaccount');
    }

}
