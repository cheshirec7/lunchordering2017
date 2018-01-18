<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\PaymentRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\Admin\StorePaymentsRequest;
use App\Http\Requests\Admin\UpdatePaymentsRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    /**
     * @var PaymentRepository $payments
     * @var AccountRepository $accounts
     * @var string $userDateFormat
     */
    protected $payments;
    protected $accounts;
    protected $userDateFormat = 'm/d/Y';

    /**
     * @param PaymentRepository $payments
     * @param AccountRepository $accounts
     */
    public function __construct(PaymentRepository $payments,
                                AccountRepository $accounts)
    {
        $this->payments = $payments;
        $this->accounts = $accounts;
    }

    /**
     * @param  $aid
     * @return mixed
     */
    public function show($aid)
    {
        return DataTables::of($this->payments->getForDataTable($aid))
            ->escapeColumns(['credit_desc'])
            ->addColumn('actions', function ($payment) {
                return $payment->action_buttons;
            })
            ->editColumn('credit_amt', function ($payment) {
                return money_format('$%.2n', $payment->credit_amt / 100);
            })
            ->editColumn('fee', function ($payment) {
                return money_format('$%.2n', $payment->fee / 100);
            })
            ->editColumn('credit_date', function ($payment) {
                return $payment->credit_date->format('Y-m-d');
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
                        return 'Paypal';
                        break;
                    case 4:
                        return 'Adjustment';
                        break;
                    default:
                        return '(undefined)';
                        break;
                }
            })
            ->make(true);
    }

    /**
     * Get the message after create/edit/delete to display to the user.
     *
     * @param  $payment
     * @return string
     */
    public function getMessage($payment)
    {
        $msg = $payment->credit_date->toDateString();
        $msg .= ' for ';
        $msg .= money_format('$%.2n', $payment->credit_amt / 100);
        return $msg;
    }

    /**
     * Display a listing of Payments.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        delete FROM los_payments where credit_date > '2017-12-03'
//        $this->accounts->updateAccountAggregates(0);

        $accounts = $this->accounts->getForSelectHasOrders();
        $aid = $request->input('aid', 0);
        $dt = Carbon::today()->format($this->userDateFormat);

        return view('admin.payments.index')
            ->withAccounts($accounts)
            ->withAccountid($aid)
            ->withDate($dt);
    }

    /**
     * Show the form for creating new Payment.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $aid = $request->input('aid', 0);
        $account = $this->accounts->getForSelectForAccount($aid);

        if (!$account)
            return redirect()->route('admin.payments.index', ['aid' => 0])
                ->withFlashWarning('Unable to create a payment for account ' . $aid);

        $dt = Carbon::today()->format($this->userDateFormat);

        return view('admin.payments.create')
            ->withAccount($account)
            ->withAccountid($aid)
            ->withDate($dt);
    }

    /**
     * Store a newly created Payment in storage.
     *
     * @param  StorePaymentsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentsRequest $request)
    {
        $req = $request->only('account_id', 'pay_method', 'credit_date', 'credit_desc', 'credit_amt');
        $req['credit_amt'] = $request->input('credit_amt') * 100;

        try {
            $credit_date = Carbon::createFromFormat($this->userDateFormat, $req['credit_date'])->setTime(0,0,0);
        } catch (\Exception $err) {
            return redirect()->back()->withInput()->withFlashDanger('Invalid received date: ' . $err->getMessage());
        }

        $req['credit_date'] = $credit_date;

        $payment = Payment::create($req);
        $this->accounts->updateAccountAggregates($payment->account_id);

        return redirect()->route('admin.payments.index', ['aid' => $payment->account_id])
            ->withFlashSuccess('A payment on '.$this->getMessage($payment).' was added.');
    }

    /**
     * Show the form for editing a Payment.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            $msg = 'Unable to edit a payment with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.payments.index')
                ->withFlashWarning($msg);
        }

        $account = $this->accounts->getForSelectForAccount($payment->account_id);

        return view('admin.payments.edit')
            ->withPayment($payment)
            ->withAccount($account);
    }

    /**
     * Add Payment records for all Accounts that have a current balance
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request)
    {
        $req = $request->only('credit_date', 'credit_desc');

        try {
            $req['credit_date'] = Carbon::createFromFormat($this->userDateFormat, $req['credit_date']);
        } catch (\Exception $err) {
            return redirect()->back()->withInput()->withFlashDanger('Invalid received date: ' . $err->getMessage());
        }

        $affected = $this->accounts->createCurBalAdjRecsForAllAccounts($req);

        return redirect()->route('admin.payments.index')
            ->withFlashSuccess($affected.' payment records for accounts with a balance due were created.');
    }

    /**
     * Update Payment in storage.
     *
     * @param  UpdatePaymentsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentsRequest $request, $id)
    {
        $req = $request->only('pay_method', 'credit_date', 'credit_desc', 'credit_amt');
        $req['credit_amt'] = $request->input('credit_amt') * 100;

        try {
            $req['credit_date'] = Carbon::createFromFormat($this->userDateFormat, $req['credit_date']);
        } catch (\Exception $err) {
            return redirect()->back()->withInput()->withFlashDanger('Invalid received date: ' . $err->getMessage());
        }

        $payment = Payment::find($id);
        if (!$payment) {
            $msg = 'Unable to update a payment with id ' . $id;
            Log::error($msg);
            return redirect()->route('admin.payments.index')
                ->withFlashWarning($msg);
        }

        $payment->update($req);
        $this->accounts->updateAccountAggregates($payment->account_id);

        return redirect()->route('admin.payments.index', ['aid' => $payment->account_id])
            ->withFlashSuccess('A payment on '.$this->getMessage($payment).' was updated.');
    }

    /**
     * Remove a Payment from storage using AJAX. The client refreshes
     * itself, so no redirect. Flash a status message to the session.
     *
     * @param  int $id
     * @return mixed
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            $msg = 'Unable to delete payment with id ' . $id;
            session()->flash('flash_warning', $msg);
            return;
        }

        $pay_acctid = $payment->account_id;
        $payment->delete();
        $this->accounts->updateAccountAggregates($pay_acctid);

        session()->flash('flash_info', 'A payment on ' . $this->getMessage($payment) . ' was deleted.');
    }
}
