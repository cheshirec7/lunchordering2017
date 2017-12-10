<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\LunchDateMenuItemRepository;
use App\Repositories\LunchDateRepository;
use App\Repositories\NoLunchExceptionRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * @var OrderRepository $orders
     * @var OrderDetailRepository $orderdetails
     * @var LunchDateRepository $lunchdates
     * @var NoLunchExceptionRepository $nles
     * @var UserRepository $users
     * @var AccountRepository $accounts
     * @var LunchDateMenuItemRepository
     */
    protected $orders;
    protected $orderdetails;
    protected $lunchdates;
    protected $nles;
    protected $users;
    protected $accounts;
    protected $lunchdatemenuitems;

    /**
     * @param OrderRepository $orders
     * @param OrderDetailRepository $orderdetails
     * @param LunchDateRepository $lunchdates
     * @param NoLunchExceptionRepository $nles
     * @param UserRepository $users
     * @param AccountRepository $accounts
     * @param LunchDateMenuItemRepository $lunchdatemenuitems
     */
    public function __construct(OrderRepository $orders,
                                OrderDetailRepository $orderdetails,
                                LunchDateRepository $lunchdates,
                                NoLunchExceptionRepository $nles,
                                UserRepository $users,
                                AccountRepository $accounts,
                                LunchDateMenuItemRepository $lunchdatemenuitems)
    {
        $this->orders = $orders;
        $this->orderdetails = $orderdetails;
        $this->lunchdates = $lunchdates;
        $this->nles = $nles;
        $this->users = $users;
        $this->accounts = $accounts;
        $this->lunchdatemenuitems = $lunchdatemenuitems;
    }

    /**
     * Build weeks of orders for all users
     *
     * @param  Carbon $start_week
     * @param  Carbon $end_week
     * @return string
     */
    private function buildTheTable($aid, Carbon $start_week, Carbon $end_week)
    {
        $account = $this->accounts->getForOrders($aid);
        $users = $this->users->getForOrders($aid);
        $lunchdates = $this->lunchdates->getForOrders($start_week, $end_week);
        $nles = $this->nles->getForOrders($start_week, $end_week);
        $orders = $this->orders->getForOrders($start_week, $end_week);
        $today = Carbon::today();
        $providers_row = '';
        $lunchdates_row = '';
        $providerIDs = array(0, 0, 0, 0, 0);
        $res = '';
        $loopDate = $start_week->copy();

        // build header, loop mon-fri
        for ($i = 0; $i < 5; $i++) {
            $todayclass = '';
            if ($loopDate->eq($today))
                $todayclass = ' class="today"';
            $providers_row .= '<th>';
            foreach ($lunchdates as $lunchdate) {
                if ($lunchdate->provide_date->eq($loopDate)) {
                    $providers_row .= '<a target="_blank" href="' . $lunchdate->provider_url . '">';
                    $providers_row .= '<img src="/img/providers/' . $lunchdate->provider_image . '" alt="' . $lunchdate->provider_name . '" title="' . $lunchdate->provider_name . '"></a>';
                    $providerIDs[$i] = $lunchdate->prov_id;
                    break;
                }
            }

            if ($providerIDs[$i] == 0) {
                $providers_row .= '<img src="/img/providers/nolunches2017.png" alt="No Lunches Scheduled" title="No Lunches Scheduled">';
            }

            $providers_row .= '</th>';
            $lunchdates_row .= '<th' . $todayclass . '><div>' . $loopDate->format("D") . '</div> ' . $loopDate->format("M j") . '</th>';
            $loopDate->addDay();
        }

        $res .= '<tr class="providers"><th class="usercol"></th>' . $providers_row . '</tr>';
        $res .= '<tr class="lunchdates"><th class="usercol">Name</th>' . $lunchdates_row . '</tr>';

        // build body
        try {
            $aniDate = Carbon::createFromFormat('Ymd', session('ani-date'))->setTime(0, 0, 0);
            $aniUserid = session('ani-userid');
        } catch (\Exception $e) {
            $aniDate = null;
            $aniUserid = 0;
        }

        foreach ($users as $user) {
            $res .= '<tr><td colspan="6" class="userrow">' . $user->first_last . '</td></tr>';
            $res .= '<tr>';
            $res .= '<td class="usercol">' . $user->first_name . '<br />' . $user->last_name . '</td>';

            $loopDate = $start_week->copy();
            for ($i = 0; $i < 5; $i++) {
                $animate = ($aniDate && $loopDate->eq($aniDate) && $user->id == $aniUserid);
                $res .= $this->getOrderCellHTML($aid, $loopDate, $today, $lunchdates, $nles, $user, $orders, $account, $animate);
                $loopDate->addDay();
            }
            $res .= '</tr>';
        }

        return $res;
    }

    /**
     * Get the HTML for a cell
     *
     * @return string
     */
    private function getOrderCellHTML(int $aid, Carbon $cur_date, Carbon $today,
                                      Collection $lunchdates, Collection $nles, User $user,
                                      Collection $orders, Account $account, bool $animate): string
    {
        $lunchdate_ptr = null;
        $nle_ptr = null;
        $order_ptr = null;

        // a lunch date must be defined for anything to show on schedule
        foreach ($lunchdates as $lunchdate) {
            if ($lunchdate->provide_date->eq($cur_date)) {
                $lunchdate_ptr = &$lunchdate;
                break;
            }
        }

        if (is_null($lunchdate_ptr)) {
            if ($cur_date->eq($today))
                return '<td class="today"><div class="spacer">No<br/>Lunches<br/>Scheduled</div></td>';
            else
                return '<td><div class="spacer">No<br/>Lunches<br/>Scheduled</div></td>';
        }

        // check for exception
        foreach ($nles as $nle) {
            if ($nle->exception_date->eq($cur_date)) {
                if ($user->grade_id == $nle->grade_id) {
                    $nle_ptr = &$nle;
                    break;
                }
            }
        }

        // check for order
        if (is_null($nle_ptr)) {
            foreach ($orders as $order) {
                if ($order->order_date->eq($cur_date) && ($user->id == $order->user_id)) {
                    $order_ptr = &$order;
                    break;
                }
            }
        }

        $body = '';
        $editable = false;
        if ($nle_ptr) {
            $body .= '<div class="nlereason">' . $nle_ptr->reason . '</div>';
            if ($nle_ptr->description)
                $body .= '<div class="nledesc">' . $nle_ptr->description . '</div>';
        } else if ($order_ptr) {
            $body .= '<div class="lunchtext">' . $order_ptr->short_desc . '</div>';
            $editable = ($cur_date->gt($today) && !$lunchdate_ptr->orders_placed);
            if ($editable)
                $body .= '<div class="fa fa-edit"></div>';
        } else if ($cur_date->gt($today) && !$lunchdate_ptr->orders_placed && $lunchdate_ptr->allow_orders) {
            if ($user->allowed_to_order && $account->allow_new_orders && $account->active) {
                $body .= '<div class="fa fa-plus-circle"></div>';
                $body .= '<div class="ordertext">Order</div>';
                $editable = true;
            } else {
                $body .= '<div class="nlo">Lunch<br />Ordering<br />Disabled</div>';
            }
        } else if ($lunchdate_ptr->allow_orders) {
            $body .= '<div class="nlo">No Lunch<br />Ordered</div>';
        } else if ($lunchdate_ptr->prov_id == config('app.provider_lunchprovided_id')) {
            $body .= '<div class="spacer">Lunch Provided By School</div>';
        }

        if ($lunchdate_ptr->additional_text) {
            $body .= '<div class="addltxt">' . $lunchdate_ptr->additional_text . '</div>';
        }

        if ($lunchdate_ptr->extended_care_text) {
            $body .= '<div class="extcare">' . $lunchdate_ptr->extended_care_text . '</div>';
        }

        $classes = '';
        if ($cur_date->eq($today))
            $classes .= 'today ';

        if ($editable)
            $classes .= 'enabled ';

        if ($animate)
            $classes .= 'cell-animate ';

        if ($classes)
            $classes = ' class="' . $classes . '"';

        if ($editable)
            return '<td' . $classes . '><a href="/orders/' . $cur_date->format('Ymd') . '/' . $user->id . '/' . $aid . '">' . $body . '</a></td>';
        else
            return '<td' . $classes . '>' . $body . '</td>';
    }

    /**
     * Display a week of orders
     * start_week must always be a Monday
     *
     * @param Request $request
     * @param Carbon $start_week
     * @param Carbon $cur_week
     * @return \Illuminate\Http\Response
     */
    private function viewWeekSchedule(Request $request, Carbon $start_week, Carbon $cur_week)
    {
        $end_week = $start_week->copy()->addDay(4);
        $next_week = $start_week->copy()->addWeek(1);
        $prev_week = $start_week->copy()->subWeek(1);

        if ($end_week->year != $start_week->year) {
            $daterange = $start_week->format('M. jS, Y') . ' to ' . $end_week->format('M. jS, Y');
        } else if ($end_week->month != $start_week->month) {
            $daterange = $start_week->format('M. j') . ' - ' . $end_week->format('M. j, Y');
        } else
            $daterange = $start_week->format('F j') . ' - ' . $end_week->format('j, Y');

        $accounts = null;
        $avatar = null;
        if (Gate::allows('manage-backend')) {
            $aid = $request->input('aid', Auth::id());
            $accounts = $this->accounts->getForSelect(Auth::id() == 1);
            $account = Account::find($aid);
            $avatar = \Gravatar::get($account->email, 'orderlunches');
        } else {
            $aid = Auth::id();
        }

        return view('orders.index')
            ->withPrevweek($prev_week)
            ->withDaterange($daterange)
            ->withNextweek($next_week)
            ->withCurweek($cur_week)
            ->withAccounts($accounts)
            ->withAccountid($aid)
            ->withThetable($this->buildTheTable($aid, $start_week, $end_week))
            ->withAvatar($avatar);
    }

    /**
     * Get the Monday of the current week
     *
     * @return Carbon
     */
    private function getCurWeek()
    {
        $start = Carbon::today();
        if ($start->isSunday())
            return $start->addDays(1);
        else if ($start->isSaturday())
            return $start->addDays(2);
        else
            return $start->startOfWeek();
    }

    /**
     * Display orders for the current week for all users.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_week = $this->getCurWeek();
        return $this->viewWeekSchedule($request, $start_week, $start_week);
    }

    /**
     * Display a week of orders using the passed in date (yearweekday)
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        try {
            $start_week = Carbon::createFromFormat('Ymd', $id)->setTime(0, 0, 0);
        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->withFlashDanger('Invalid date specified.');
        }

        $cur_week = $this->getCurWeek();
        return $this->viewWeekSchedule($request, $start_week->startOfWeek(), $cur_week);
    }


    private function doRedirectDanger($msg)
    {
        return redirect()
//            ->route('orders.index')
            ->back()
            ->withFlashDanger($msg);
    }

    /**
     * Display a day for date / user
     *
     * @param int $date_ymd
     * @param int $uid
     * @param int $aid
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function getDateUser(int $date_ymd, int $uid, int $aid)
    {
        $user = User::find($uid);
        if (!$user)
            return $this->doRedirectDanger('Invalid user specified.');

        $avatar = null;
        if ($user->account_id != Auth::id()) {
            if (!(Gate::allows('manage-backend')))
                throw new AuthorizationException();

            $account = Account::find($aid);
            if (!$account)
                return $this->doRedirectDanger('Invalid account specified.');
            $avatar = \Gravatar::get($account->email, 'orderlunches');
        }

        try {
            $order_date = Carbon::createFromFormat('Ymd', $date_ymd)->setTime(0, 0, 0);
        } catch (\Exception $e) {
            return $this->doRedirectDanger('Invalid date specified.');
        }

        $lunchdate = $this->lunchdates->getForOrder($order_date);
        if (!$lunchdate)
            return $this->doRedirectDanger('Invalid lunch date.');
        if ($lunchdate->orders_placed) return $this->doRedirectDanger('Orders have been placed for that date.');

        $order = $this->orders->getOrder($uid, $order_date);
        $od_count = 0;
        if ($order) {
            $orderdetails = $this->orderdetails->getForOrder($order->id);
            $od_count = count($orderdetails);
            $menuitems = $this->lunchdatemenuitems->getForOrder($lunchdate->lunchdate_id,
                $orderdetails->pluck('menuitem_id')->all());
        } else {
            $orderdetails = null;
            $menuitems = $this->lunchdatemenuitems->getForOrder($lunchdate->lunchdate_id, null);
        }
        $mi_count = count($menuitems);
        $totalcount = $mi_count + $od_count;
        if ($totalcount == 0)
            return $this->doRedirectDanger('No active menu items found for that lunch date.');

        $checkeditems = '';
        $uncheckeditems1 = '';
        $uncheckeditems2 = '';

        if ($od_count > 0) {
            foreach ($orderdetails as $orderdetail) {
                $checkeditems .= view('orders.checkeditems')
                    ->withOrderdetail($orderdetail)
                    ->render();
            }
        }

        $i = 0;
        $halftotal = ($totalcount / 2) - $od_count;
        foreach ($menuitems as $menuitem) {
            if ($i < $halftotal) {
                $uncheckeditems1 .= view('orders.uncheckeditems')
                    ->withMenuitem($menuitem)
                    ->render();
            } else {
                $uncheckeditems2 .= view('orders.uncheckeditems')
                    ->withMenuitem($menuitem)
                    ->render();
            }
            $i++;
        }

        return view('orders.create')
            ->withOrderdate($order_date)
            ->withLunchdate($lunchdate)
            ->withUser($user)
            ->withAccountid($aid)
            ->withCheckeditems($checkeditems)
            ->withUncheckeditems1($uncheckeditems1)
            ->withUncheckeditems2($uncheckeditems2)
            ->withAvatar($avatar);
    }


    private function doOrdersShowDangerRedirect(int $id, int $aid, string $msg)
    {
        return redirect()
            ->route('orders.show', ['id' => $id, 'aid' => $aid])
            ->withFlashDanger($msg);
    }

    /**
     * Store a newly created Lunch Date in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mi_count = 0;
        if ($request['menuitems']) {
            $mi_count = count($request['menuitems']);
            if ($mi_count != count($request['qtys'])) {
                return redirect()
                    ->route('orders.index')
                    ->withFlashDanger('Invalid menuitem / qty count.');
            }
        }

        $thedateYmd = $request->input('date', 0);
        try {
            $order_date = Carbon::createFromFormat('Ymd', $thedateYmd)->setTime(0, 0, 0);
        } catch (\Exception $e) {
            return redirect()
                ->route('orders.index')
                ->withFlashDanger('Invalid date specified.');
        }

        $aid = $request->input('aid', Auth::id());
        $uid = $request->input('uid', 0);
        $user = User::find($uid);
        if (!$user)
            return $this->doOrdersShowDangerRedirect($thedateYmd, $aid, 'Invalid user specified.');

        $lunchdate = $this->lunchdates->getForOrder($order_date);
        if (!$lunchdate) {
            return $this->doOrdersShowDangerRedirect($thedateYmd, $aid, 'Invalid lunch date.');
        } else if ($lunchdate->orders_placed) {
            return $this->doOrdersShowDangerRedirect($thedateYmd, $aid, 'Orders have been placed for ' . $order_date->toDateString() . '.');
        }

        $order = $this->orders->getOrder($uid, $order_date);
        if ($order) {
            $deletedRows = OrderDetail::where('order_id', $order->id)->delete();
            $order->delete();
        }

        if ($mi_count > 0) {
            DB::beginTransaction();

            $order = Order::create([
                'account_id' => $user->account_id,
                'user_id' => $uid,
                'lunchdate_id' => $lunchdate->lunchdate_id,
                'order_date' => $order_date,
                'status_code' => config('app.status_code_unlocked'),
                'entered_by_account_id' => Auth::id(),
                'short_desc' => ' '
            ]);

            $i = 0;
            foreach ($request['menuitems'] as $menuitem) {
                OrderDetail::create([
                    'account_id' => $user->account_id,
                    'provider_id' => $lunchdate->provider_id,
                    'order_id' => $order->id,
                    'menuitem_id' => $menuitem,
                    'qty' => $request['qtys'][$i]
                ]);
                $i++;
            }
            $this->orderdetails->updateProvidersAndPrices($order->id);
            $this->orders->updateDescAndTotalPrice($order->id);
            DB::commit();
        }

        $this->accounts->updateAccountAggregates($user->account_id);

        session()->flash('ani-userid', $uid);
        session()->flash('ani-date', $thedateYmd);

        return redirect()
            ->route('orders.show', ['id' => $thedateYmd, 'aid' => $aid]);
//            ->withFlashSuccess('Order saved.');
    }
}
