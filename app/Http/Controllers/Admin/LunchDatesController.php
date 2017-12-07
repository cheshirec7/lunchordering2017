<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLunchDateRequest;
use App\Http\Requests\Admin\UpdateLunchDateRequest;
use App\Models\LunchDate;
use App\Models\LunchDateMenuItem;
use App\Repositories\LunchDateMenuItemRepository;
use App\Repositories\LunchDateRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\NoLunchExceptionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProviderRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LunchDatesController extends Controller
{
    /**
     * @var LunchDateRepository
     * @var NoLunchExceptionRepository
     * @var ProviderRepository
     * @var MenuItemRepository
     * @var LunchDateMenuItemRepository
     * @var OrderRepository
     */
    protected $lunchdates;
    protected $nle;
    protected $providers;
    protected $menuitems;
    protected $lunchdatemenuitems;
    protected $orders;

    /**
     * @param LunchDateRepository $lunchdates
     * @param NoLunchExceptionRepository $nle
     * @param ProviderRepository $providers
     * @param MenuItemRepository $menuitems
     * @param LunchDateMenuItemRepository $lunchdatemenuitems
     * @param OrderRepository $orders
     */
    public function __construct(LunchDateRepository $lunchdates,
                                NoLunchExceptionRepository $nle,
                                ProviderRepository $providers,
                                MenuItemRepository $menuitems,
                                LunchDateMenuItemRepository $lunchdatemenuitems,
                                OrderRepository $orders)
    {
        $this->lunchdates = $lunchdates;
        $this->nle = $nle;
        $this->providers = $providers;
        $this->menuitems = $menuitems;
        $this->lunchdatemenuitems = $lunchdatemenuitems;
        $this->orders = $orders;
    }

    /**
     * Display a month of Lunch Dates
     *
     * @param  Carbon $start_month
     * @return \Illuminate\Http\Response
     */
    private function viewMonthSchedule(Carbon $start_month)
    {
        $next_month = $start_month->copy()->addMonths(1);
        $prev_month = $start_month->copy()->subMonths(1);

        return view('admin.lunchdates.index')
            ->withPrevmonth($prev_month)
            ->withStartmonth($start_month)
            ->withNextmonth($next_month)
            ->withThetable($this->getScheduleMonthHTML($start_month));
    }

    /**
     * Display a month of Lunch Dates using the month of the current date
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewMonthSchedule(Carbon::today()->startOfMonth());
    }

    /**
     * Display a month of Lunch Dates using the passed in month
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $year = substr($id, 0, 4);
        $month = substr($id, 4, 2);

        try {
            $start_month = Carbon::createFromDate($year, $month, 1)->setTime(0, 0, 0);
            return $this->viewMonthSchedule($start_month);
        } catch (\Exception $e) {
            return redirect()->route('admin.lunchdates.index')
                ->withFlashDanger('Invalid date specified.');
        }
    }

    /**
     * Generate HTML for a month
     *
     * @return string
     */
    private function getScheduleMonthHTML($start_month)
    {
        $today = Carbon::today();
        $end_month = $start_month->copy()->endOfMonth();
        if ($start_month->dayOfWeek == 6)
            $start_date = $start_month->copy()->addDays(1);
        else
            $start_date = $start_month->copy()->addDays(-$start_month->dayOfWeek);
        $start_date->setTime(0, 0, 0);
        $end_date = $end_month->copy();
        if ($end_date->dayOfWeek == 0) {
            $end_date->subDays(1);
        } else {
            $end_date = $end_month->copy()->addDays(6 - $end_month->dayOfWeek);
        }

        $lunchdates = $this->lunchdates->getForScheduleMonth($start_date, $end_date);

        $nleGrades = $this->nle->getGradeExceptionsForScheduleMonth($start_date, $end_date);
//        $nleTeachers = $this->nle->getTeacherExceptionsForScheduleMonth($start_date, $end_date);

        $ordercounts = $this->orders->getOrderCountsForRange($start_date, $end_date);

        $res = '';
        $cur_date = $start_date->copy();

        while ($cur_date->lte($end_date)) {

            if ($cur_date->isSunday())
                $res .= '<tr>';
            $ld = null;

            foreach ($lunchdates as $lunchdate) {
                if ($lunchdate->provide_date->eq($cur_date)) {
                    $ld = $lunchdate;
                    break;
                }
            }

            $order_count = 0;
            foreach ($ordercounts as $oc) {
                if ($oc->order_date->eq($cur_date)) {
                    $order_count = $oc->order_count;
                    break;
                }
            }

            if ($cur_date->isWeekend()) {
                $res .= '<td></td>';
            } else {

                if ($cur_date->gt($today)) {
                    $res .= '<td class="enabled" data-date="' . $cur_date->format('Ymd') . '">';
                } else {
                    $res .= '<td>';
                }
                $res .= $this->buildCellContents($cur_date, $ld, $order_count, $nleGrades);
                $res .= '</td>';
            }

            if ($cur_date->isSaturday())
                $res .= '</tr>';
            $cur_date->addDay();
        }
        return $res;
    }

    /**
     * Generate HTML for a cell
     *
     * @param  Carbon $cur_date
     * @param  $oLunchDate
     * @param  $order_count
     * @param  $nleGrades
     *
     * @return string
     */
    private function buildCellContents(Carbon $cur_date, $oLunchDate, $order_count, $nleGrades)
    {
        $res = '<span>' . $cur_date->format('j') . '</span>';

        if ($oLunchDate) {
            $res .= '<img src="/img/providers/' . $oLunchDate->provider_image . '" alt="' . $oLunchDate->provider_name . '" title="' . $oLunchDate->provider_name . '">';

            if ($oLunchDate->additional_text) {
                $res .= '<div class="addltxt">' . $oLunchDate->additional_text . '</div>';
            }
            if ($oLunchDate->extended_care_text) {
                $res .= '<div class="extcare">' . $oLunchDate->extended_care_text . '</div>';
            }
        }

        $exceptions = '';
        foreach ($nleGrades as $nle) {
            if ($nle->exception_date->eq($cur_date)) {
                $exceptions .= $nle->grade_desc . ': ' . $nle->reason . '<br />';
            }
        }

//        foreach ($nleTeachers as $nle) {
//            if ($nle->exception_date->eq($cur_date)) {
//                $exceptions .= $nle->first_name . ' ' . $nle->last_name . ': ' . $nle->reason . '<br />';
//            }
//        }

        if ($exceptions) {
            $res .= '<div class="nle">' . substr($exceptions, 0, -6) . '</div>';
        }

        if (($oLunchDate) && ($order_count > 0)) {
            if ($oLunchDate->orders_placed)
                $res .= '<div class="oc">' . $order_count . ' Lunches Ordered</div>';
            else
                $res .= '<div class="oc">' . $order_count . ' Orders Scheduled</div>';
        }

        return $res;
    }

    /**
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getProviderMenuItemsHTMLForEdit(Request $request)
    {
        $lunchdate = LunchDate::find($request['ldid']);
        if (!$lunchdate) {
            return response()->json(array('error' => true));
        }

        $disabled = ($lunchdate->orders_placed || $this->orders->getOrderCountForDate($lunchdate->provide_date) > 0);

        if ($lunchdate->provider_id == $request['pid']) {

            $lunchdatemenuitems = $this->lunchdatemenuitems->getForScheduling($lunchdate->id);
            $v1 = view('admin.lunchdates.lunches')
                ->withMenuitems($lunchdatemenuitems)
                ->withDisabled($disabled)
                ->withChecked(true)
                ->render();

            $menuitems = $this->menuitems->getForScheduling($request['pid'], $lunchdatemenuitems->pluck('id')->all());
            $v2 = view('admin.lunchdates.lunches')
                ->withMenuitems($menuitems)
                ->withDisabled($disabled)
                ->withChecked(count($lunchdatemenuitems) == 0)
                ->render();

            return response()->json(array('error' => false, 'html' => $v1 . $v2));
        }

        $menuitems = $this->menuitems->getForScheduling($request['pid'], null);
        $v = view('admin.lunchdates.lunches')
            ->withMenuitems($menuitems)
            ->withDisabled($disabled)
            ->withChecked(true)
            ->render();
        return response()->json(array('error' => false, 'html' => $v));
    }

    /**
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getProviderMenuItemsHTMLForCreate(Request $request)
    {
        $menuitems = $this->menuitems->getForScheduling($request['pid'], null);
        $v = view('admin.lunchdates.lunches')
            ->withMenuitems($menuitems)
            ->withDisabled(false)
            ->withChecked(true)
            ->render();
        return response()->json(array('error' => false, 'html' => $v));
    }

    /**
     * If the passed in date exists, show the editing form for a Lunch Date
     * Otherwise show the the create form
     *
     * @param  int
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        try {
            $provide_date = Carbon::createFromFormat('Ymd', $id)->setTime(0, 0, 0);
        } catch (\Exception $e) {
            return redirect()->route('admin.lunchdates.index')
                ->withFlashDanger('Invalid date specified.');
        }

        $today = Carbon::today();

        if ($provide_date->isWeekend() || $provide_date->lte($today)) {
            return redirect()->route('admin.lunchdates.index')
                ->withFlashDanger('Invalid date specified.');
        }

        $lunchdate = LunchDate::where('provide_date', $provide_date)->first();
        if ($lunchdate) {

            $numorders = $this->orders->getOrderCountForDate($lunchdate->provide_date);

            if ($numorders > 0 || $lunchdate->orders_placed)
                $providers = $this->providers->getForSelectForProvider($lunchdate->provider_id);
            else {
                $providers = array('0' => '[No provider selected]') + $this->providers->getForSelect(false, false, true);
            }

            return view('admin.lunchdates.edit')
                ->withLunchdate($lunchdate)
                ->withProviders($providers);
        }

        $providers = $this->providers->getForSelect(false, true, true);

        return view('admin.lunchdates.create')
            ->withProvidedate($provide_date)
            ->withProviders($providers);
    }

    /**
     * Store a newly created Lunch Date in storage.
     *
     * @param  StoreLunchDateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLunchDateRequest $request)
    {
        $req = $request->only('provider_id', 'provide_date', 'additional_text', 'extended_care_text');
        if ($req['provider_id'] > config('app.provider_lunchprovided_id')) {
            if (count($request['menuitems']) == 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withFlashWarning('Please select at least one menu item.');
            }
        }

        $lunchdate = LunchDate::create($req);

        if ($lunchdate->provider_id > config('app.provider_lunchprovided_id')) {
            foreach ($request['menuitems'] as $menuitem) {
                $ldmi = new LunchDateMenuItem();
                $ldmi->lunchdate_id = $lunchdate->id;
                $ldmi->menuitem_id = $menuitem;
                $ldmi->save();
            }
        }

        $provide_date = Carbon::createFromFormat('Y-m-d', $req['provide_date']);
        return redirect()
            ->route('admin.lunchdates.show', ['id' => $provide_date->format('Ym')])
            ->withFlashSuccess('Provider for ' . $provide_date->format('l, F jS, Y') . ' scheduled.');
    }

    /**
     * Update Lunch Date in storage.
     *
     * @param  UpdateLunchDateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLunchDateRequest $request, $id)
    {
        $lunchdate = LunchDate::find($id);
        if (!$lunchdate) {
            $msg = 'Unable to update lunch date with id ' . $id;
            Log::error($msg);
            return redirect()
                ->route('admin.lunchdates.index')
                ->withFlashWarning($msg);
        }

        $provider_id = $request->input('provider_id', 0);
        $req = $request->only('provider_id', 'additional_text', 'extended_care_text');

        $numorders = $this->orders->getOrderCountForDate($lunchdate->provide_date);

        if ($lunchdate->orders_placed || $numorders > 0) {
            $req = array_except($req, ['provider_id']);
            $lunchdate->update($req);
            return redirect()
                ->route('admin.lunchdates.show', ['id' => $lunchdate->provide_date->format('Ym')])
                ->withFlashSuccess($lunchdate->provide_date->format('l, F jS, Y') . ' updated.');
        }

        if ($provider_id > config('app.provider_lunchprovided_id')) {
            if (count($request['menuitems']) == 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withFlashWarning('Please select at least one menu item.');
            }
        }

        $deletedRows = LunchDateMenuItem::where('lunchdate_id', $id)->delete();
        if ($provider_id == 0) {
            $lunchdate->delete();
            return redirect()
                ->route('admin.lunchdates.show', ['id' => $lunchdate->provide_date->format('Ym')])
                ->withFlashSuccess($lunchdate->provide_date->format('l, F jS, Y') . ' updated.');
        }

        $lunchdate->update($req);

        if ($lunchdate->provider_id > config('app.provider_lunchprovided_id')) {
            foreach ($request['menuitems'] as $menuitem) {
                $ldmi = new LunchDateMenuItem();
                $ldmi->lunchdate_id = $lunchdate->id;
                $ldmi->menuitem_id = $menuitem;
                $ldmi->save();
            }
        }

        return redirect()
            ->route('admin.lunchdates.show', ['id' => $lunchdate->provide_date->format('Ym')])
            ->withFlashSuccess($lunchdate->provide_date->format('l, F jS, Y') . ' updated.');
    }
}
