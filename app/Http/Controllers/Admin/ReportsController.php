<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use App\Repositories\LunchDateRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProviderRepository;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * @var AccountRepository $accounts
     * @var LunchDateRepository $lunchdates
     * @var OrderRepository $orders
     * @var OrderDetailRepository $orderdetails
     * @var ProviderRepository $providers
     * @var PaymentRepository $payments
     * @var Fpdf $fpdf
     */

    protected $accounts;
    protected $lunchdates;
    protected $orders;
    protected $orderdetails;
    protected $providers;
    protected $payments;
    protected $fpdf;

    /**
     * Create a new controller instance.
     *
     * @param  AccountRepository $accounts
     * @param  LunchDateRepository $lunchdates
     * @param  OrderRepository $orders
     * @param  OrderDetailRepository $orderdetails
     * @param  ProviderRepository $providers
     * @param  PaymentRepository $payments
     * @param  FPDF $fpdf
     */
    public function __construct(AccountRepository $accounts,
                                LunchDateRepository $lunchdates,
                                OrderRepository $orders,
                                OrderDetailRepository $orderdetails,
                                ProviderRepository $providers,
                                PaymentRepository $payments,
                                Fpdf $fpdf)
    {
        $this->accounts = $accounts;
        $this->lunchdates = $lunchdates;
        $this->orders = $orders;
        $this->orderdetails = $orderdetails;
        $this->providers = $providers;
        $this->payments = $payments;
        $this->fpdf = $fpdf;
    }

    /**
     * Display report selection.
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $accs = $this->accounts->getForSelectHasOrders();
        $dates = $this->lunchdates->getForSelectDatesWithOrders();
        $paymentdates = $this->payments->getForSelectDatesWithPayments();

//        Lunch Orders By Teacher
        $reports = [
            0 => '- Select -',
            1 => 'Lunch Orders By Provider',
            2 => 'Lunch Orders By Grade',
            3 => 'Account Balances',
            4 => 'Account Details',
            5 => 'Lunch Labels (Avery 5160)',
            6 => 'Payments By Date'
        ];

        return view('reports.adminindex')
            ->withReports($reports)
            ->withAccounts($accs)
            ->withDates($dates)
            ->withPaymentdates($paymentdates);
    }

    public function show(Request $request, $id)
    {
        switch ($id) {

            case 1:
                $title = 'Lunch Orders By Provider';
                try {
                    $thedate = Carbon::createFromFormat('Y-m-d', $request->input('d'))->setTime(0, 0, 0);
                } catch (\Exception $err) {
                    $thedate = Carbon::today();
                }
                $date = $thedate->format('l, F jS, Y');
                $provider = $this->providers->providerForDate($thedate);
                $items = $this->orders->adminProviderReport($thedate);
                return view('reports.providerorders')
                    ->withTitle($title)
                    ->withThedate($date)
                    ->withProvider($provider)
                    ->withItems($items)
                    ->render();
                break;

//            case 2: $title = 'Lunch Orders By Teacher';
//                $date = date_create($request->d)->format('l, F jS, Y');
//                $items = $this->orders->adminOrdersByTeacherReport($request->d);
//                return view('admin.reports.ordersbyteacher', ['title'=> $title, 'thedate'=>$date, 'items'=>$items])->render();
//                break;

            case 2:
                $title = 'Lunch Orders By Grade';
                try {
                    $thedate = Carbon::createFromFormat('Y-m-d', $request->input('d'))->setTime(0, 0, 0);
                } catch (\Exception $err) {
                    $thedate = Carbon::today();
                }
                $date = $thedate->format('l, F jS, Y');
                $provider = $this->providers->providerForDate($thedate);
                $items = $this->orders->adminOrdersByGradeReport($thedate);
                return view('reports.ordersbygrade')
                    ->withTitle($title)
                    ->withThedate($date)
                    ->withProvider($provider)
                    ->withItems($items)
                    ->render();
                break;

            case 3:
                $title = 'Account Balances';
                $items = $this->accounts->adminAccountBalancesReport();
                return view('reports.accountbalances')
                    ->withTitle($title)
                    ->withItems($items)
                    ->render();
                break;

            case 4:
                $title = 'Account Details';
                $account = $this->accounts->adminAccountDetailReport($request->a);
                $payments = $this->payments->adminAccountDetailReport($request->a);
                $orders = $this->orderdetails->adminAccountDetailReport($request->a);
                return view('reports.accountdetails')
                    ->withTitle($title)
                    ->withAccount($account)
                    ->withPayments($payments)
                    ->withOrders($orders)
                    ->render();
                break;

            case 5: //Avery 5160 labels

                try {
                    $thedate = Carbon::createFromFormat('Y-m-d', $request->input('d'))->setTime(0, 0, 0);
                } catch (\Exception $err) {
                    $thedate = Carbon::today();
                }
                $items = $this->orders->adminLunchLabels($thedate);
                $this->fpdf->AddPage('P', 'Letter');
                $this->fpdf->SetFont('Helvetica', '', 8);
                $this->fpdf->SetMargins(0, 0);
                $this->fpdf->SetAutoPageBreak(false);
                $left = 4.7625; // 0.1875" in mm
                $top = 12.7; // 0.5" in mm
                $width = 66.675; // 2.625" in mm
                $height = 25.4; // 1.0" in mm
                $hgap = 3.175; // 0.125" in mm
                $vgap = 0.0;
                $x = $y = 0;

                foreach ($items as $item) {
                    if ($item->grade_desc == '(unassigned)')
                        $text = sprintf("%s %s\n%s", $item->first_name, $item->last_name, $item->short_desc);
                    else
                        $text = sprintf("%s\n%s %s\n%s", $item->grade_desc, $item->first_name, $item->last_name, $item->short_desc);

                    $this->fpdf->SetXY($left + (($width + $hgap) * $x), $top + (($height + $vgap) * $y));
                    $this->fpdf->MultiCell($width, 5, $text, 0, 'C');

                    $x++;
                    if ($x == 3) {
                        $y++;
                        $x = 0;
                        if ($y == 10) {
                            $x = 0;
                            $y = 0;
                            $this->fpdf->AddPage('P', 'Letter');
                        }
                    }
                }
                $this->fpdf->Output('D', 'lunchlabels' . $thedate->format('Ymd') . '.pdf');

                break;

            case 6:
                $title = 'Payments By Date';
                try {
                    $thedate = Carbon::createFromFormat('Y-m-d', $request->input('d'))->setTime(0, 0, 0);
                } catch (\Exception $err) {
                    $thedate = Carbon::today();
                }
                $items = $this->payments->adminPaymentsByDateReport($thedate);
                return view('reports.paymentsbydate')
                    ->withTitle($title)
                    ->withThedate($thedate)
                    ->withItems($items)
                    ->render();
                break;
        }

        return 'Invalid Report';
    }
}
