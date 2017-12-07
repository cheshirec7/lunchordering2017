<?php

namespace App\Http\Controllers;

use App\Repositories\LunchDateRepository;
use App\Repositories\NoLunchExceptionRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @var OrderRepository $orders
     * @var LunchDateRepository $lunchdates
     * @var NoLunchExceptionRepository $nles
     */
    protected $orders;
    protected $lunchdates;
    protected $nles;

    /**
     * @param OrderRepository $orders
     * @param LunchDateRepository $lunchdates
     * @param NoLunchExceptionRepository $nles
     */
    public function __construct(OrderRepository $orders,
                                LunchDateRepository $lunchdates,
                                NoLunchExceptionRepository $nles)
    {
        $this->orders = $orders;
        $this->lunchdates = $lunchdates;
        $this->nles = $nles;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     *
     */
    public function doReport(Request $request)
    {
        $report = '';
        $thedate = Carbon::today();

        $lunchdates = $this->lunchdates->getForReport($thedate);
        $nles = $this->nles->getForReport($thedate);
        $orders = $this->orders->getForReport($thedate);

        foreach ($lunchdates as $lunchdate) {

            $date_group = '';
            $numprinted = 0;

            $date_group .= '<div><b>' . $lunchdate->lunch_date . '</b> (' . $lunchdate->provider_name . ')</div>';
            $date_group .= '<div>';
            if ($lunchdate->additional_text) {
                if ($request->rpttype > 0)
                    $numprinted++;
                $date_group .= $lunchdate->additional_text;
            }

            if ($lunchdate->extended_care_text) {
                if ($request->rpttype > 0)
                    $numprinted++;
                $date_group .= ' * ' . $lunchdate->extended_care_text . ' * ';
            }

            $date_group .= '</div>';

            foreach ($nles as $nle) {
                if ($nle->exception_date == $lunchdate->provide_date) {
                    if ($request->rpttype > 0)
                        $numprinted++;
                    $date_group .= '<div>' . $nle->first_name . ' ' . $nle->last_name . ' - ' . $nle->reason . ' - ' . $nle->description . '</div>';
                }
            }

            foreach ($orders as $order) {
                if ($order->order_date == $lunchdate->provide_date) {
                    $numprinted++;
                    $date_group .= '<div>' . $order->first_name . ' ' . $order->last_name . ' - ' . $order->short_desc;
                    if ($order->status_code == 0)
                        $date_group .= ' <i>[scheduled]</i>';
                    $date_group .= '</div>';
                }
            }

            if ($numprinted == 0)
                $date_group .= '<div>No Lunches Ordered</div>';

            $date_group .= '<div>&nbsp;</div>';

            if ($request->rpttype == 2 || $numprinted > 0) {
                $report .= $date_group;
            }
        }
        return view('reports.accountorders', ['title' => 'Upcoming Lunch Orders', 'report' => $report]);
    }
}
