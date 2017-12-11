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
     * Build weeks of orders for all users for an account
     *
     * @param Account $account
     * @param Collection $users
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return string
     */
    private function buildTheWeeksUsersTable(Account $account, Collection $users, Carbon $start_date, Carbon $end_date): string
    {
        $today = Carbon::today();
//        dd($today);
        $lunchdates = $this->lunchdates->getForOrders($start_date, $end_date);
        $nles = $this->nles->getForOrders($start_date, $end_date);
        $orders = $this->orders->getForOrders($start_date, $end_date);
        $providers_row = '';
        $lunchdates_row = '';
        $providerIDs = array(0, 0, 0, 0, 0);
        $res = '';
        $loopDate = $start_date->copy();

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

            $loopDate = $start_date->copy();
            for ($i = 0; $i < 5; $i++) {
                $animate = ($aniDate && $loopDate->eq($aniDate) && $user->id == $aniUserid);
                $res .= $this->getOrderCellHTML($account, $user, $lunchdates, $nles, $orders,
                    $loopDate, $today, $animate, true);
                $loopDate->addDay();
            }
            $res .= '</tr>';
        }

        return $res;
    }

    /**
     * Build a month orders for a user
     *
     * @param Account $account
     * @param User $user
     * @param Carbon $start_month
     * @return string
     */
    private function buildTheMonthUserTable(Account $account, User $user, Carbon $start_month): string
    {
        $today = Carbon::today();
        $start_date = $start_month->copy()->startOfMonth()->startOfWeek();
        $end_date = $start_month->copy()->endOfMonth()->endOfWeek();

        $lunchdates = $this->lunchdates->getForOrders($start_date, $end_date);
        $nles = $this->nles->getForOrders($start_date, $end_date);
        $orders = $this->orders->getForOrders($start_date, $end_date);
        $res = '';

        try {
            $aniDate = Carbon::createFromFormat('Ymd', session('ani-date'))->setTime(0, 0, 0);
            $aniUserid = session('ani-userid');
        } catch (\Exception $e) {
            $aniDate = null;
            $aniUserid = 0;
        }

        $loopDate = $start_date;
        while ($loopDate->lte($end_date)) {
            if ($loopDate->isWeekend()) {
                $loopDate->addDay();
                continue;
            }
            if ($loopDate->isMonday())
                $res .= '<tr>';
            $animate = ($aniDate && $loopDate->eq($aniDate) && $user->id == $aniUserid);

            $res .= $this->getOrderCellHTML($account, $user, $lunchdates, $nles, $orders,
                $loopDate, $today, $animate, false);
            if ($loopDate->isFriday())
                $res .= '</tr>';
            $loopDate->addDay();
        }

        return $res;
    }

    /**
     * Get the HTML for a cell
     * @param Account $account
     * @param User $user
     * @param Collection $lunchdates
     * @param Collection $nles
     * @param Collection $orders
     * @param Carbon $cur_date
     * @param Carbon $today
     * @param bool $animate
     * @param bool $forWeeks
     * @return string
     */
    private function getOrderCellHTML(Account $account, User $user,
                                      Collection $lunchdates, Collection $nles, Collection $orders,
                                      Carbon $cur_date, Carbon $today,
                                      bool $animate, bool $forWeeks): string
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

            if ($cur_date->gt($today))
                return '<td class="white"></td>';

            return '<td><div class="spacer"></div></td>';
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

        if ($forWeeks) {
            $body = '';
        } else {
            $body = '<div class="dayno">' . $cur_date->day . '</div>';
        }
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
                if (!$forWeeks) {
                    $body .= '<img class="provider" src="/img/providers/' .
                        $lunchdate->provider_image . '" alt="' .
                        $lunchdate->provider_name . '" title="' . $lunchdate->provider_name . '">';
                }
                $body .= '<i class="fa fa-plus-circle"></i>';
                if ($forWeeks) {
                    $body .= '<div class="ordertext">Order</div>';
                }
                $editable = true;
            } else {
                $body .= '<div class="nlo">Lunch<br />Ordering<br />Disabled</div>';
            }
        } else if ($lunchdate_ptr->allow_orders) {
            $body .= '<div class="nlo">No Lunch<br />Ordered</div>';
        } else if ($lunchdate_ptr->prov_id == config('app.provider_lunchprovided_id')) {
            if ($forWeeks) {
                $body .= '<div class="spacer">Lunch Provided By School</div>';
            } else {
                $body .= '<div><img class="provider" src="/img/providers/' .
                    $lunchdate->provider_image . '" alt="' .
                    $lunchdate->provider_name . '" title="' . $lunchdate->provider_name . '"></div>';
                $body .= '<div class="provided">Lunch Provided By School</div>';
            }
        }

        if ($lunchdate_ptr->additional_text) {
            $body .= '<div class="addltxt">' . $lunchdate_ptr->additional_text . '</div>';
        }

        if ($lunchdate_ptr->extended_care_text) {
            $body .= '<div class="extcare">' . $lunchdate_ptr->extended_care_text . '</div>';
        }

        $classes = '';
        if ($cur_date->gte($today))// || ($cur_date->lt($today) && $editable))
            $classes .= 'white ';

        if ($cur_date->eq($today))
            $classes .= 'today ';

        if ($editable)
            $classes .= 'enabled ';

        if ($animate)
            $classes .= 'cell-animate ';

        if ($classes)
            $classes = ' class="' . $classes . '"';

        if ($editable)
            return '<td' . $classes . '><a href="/orders/' . $cur_date->format('Ymd') . '/' . $user->id . '/' . $account->id . '">' . $body . '</a></td>';
        else
            return '<td' . $classes . '>' . $body . '</td>';
    }

    /**
     * Display a week of orders
     * start_week must always be a Monday
     *
     * @param Account $account
     * @param Collection $users
     * @param Carbon $start_week
     * @param Carbon $cur_week
     * @param array $accounts
     * @param string $avatar
     * @return \Illuminate\Http\Response
     */
    private function viewWeekSchedule(Account $account, Collection $users,
                                      Carbon $start_week, Carbon $cur_week,
                                      array $accounts = null, string $avatar = null,
                                      bool $showViewBy)
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

        return view('orders.index')
            ->withPrevweek($prev_week)
            ->withDaterange($daterange)
            ->withNextweek($next_week)
            ->withCurweek($cur_week)
            ->withAccounts($accounts)
            ->withAccountid($account->id)
            ->withThetable($this->buildTheWeeksUsersTable($account, $users, $start_week, $end_week))
            ->withAvatar($avatar)
            ->withShowviewby($showViewBy);
    }

    /**
     * Display a month of orders for a single user
     * @param Carbon $start_date
     * @param Account $account
     * @param Collection $users
     * @param array $accounts
     * @param string $avatar
     * @return \Illuminate\Http\Response
     */
    private function viewMonthSchedule(Carbon $start_date,
                                       Account $account, Collection $users,
                                       array $accounts = null, string $avatar = null,
                                       bool $showViewBy)
    {
        $cur_month = $start_date->startOfMonth();
        $next_month = $cur_month->copy()->addMonth(1);
        $prev_month = $cur_month->copy()->subMonth(1);
        return view('orders.bymonth')
            ->withPrevmonth($prev_month)
            ->withNextmonth($next_month)
            ->withCurmonth($cur_month)
            ->withAccounts($accounts)
            ->withAccountid($account->id)
            ->withUser($users->first())
            ->withThetable($this->buildTheMonthUserTable($account, $users->first(), $cur_month))
            ->withAvatar($avatar)
            ->withShowviewby($showViewBy);
    }

    /**
     * Get the Monday of the "current" week
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
     * Get globals
     *
     * @param Request $request
     * @param $aid
     * @param $accounts
     * @param $account
     * @param $users
     * @param $avatar
     * @return mixed
     */
    private function getGlobals(Request $request, &$account, &$users, &$accounts, &$avatar)
    {
        if (Gate::allows('manage-backend')) {
            $aid = $request->input('aid', Auth::id());
            if (Auth::id() != $aid)
                $account = Account::find($aid);
            else
                $account = $request->user();
            $accounts = $this->accounts->getForSelect(Auth::id() == 1);
            $avatar = \Gravatar::get($account->email, 'orderlunches');
        } else {
            $aid = Auth::id();
            $account = $request->user();
        }
        $users = $this->users->getForOrders($aid);
    }

    /**
     * Display orders for the current week for all users.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $account = $users = $accounts = $avatar = null;
        $this->getGlobals($request, $account, $users, $accounts, $avatar);

        $viewBy = $request->input('vb', 'w');
        $user_count = count($users);
        if ($user_count > 1 || ($user_count == 1 && $viewBy == 'w')) {
            session(['viewby' => 'w']);
            $start_week = $this->getCurWeek();
            return $this->viewWeekSchedule($account, $users, $start_week, $start_week, $accounts, $avatar, $user_count == 1);
        } else {
            session(['viewby' => 'm']);
            return $this->viewMonthSchedule(Carbon::today(), $account, $users, $accounts, $avatar, $user_count == 1);
        }
    }

    /**
     * Display a week of orders using the passed in date (yearweekday)
     * @param Request $request
     * @param int $id (Ymd format)
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        try {
            $start_date = Carbon::createFromFormat('Ymd', $id)->setTime(0, 0, 0);
        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->withFlashDanger('Invalid date specified.');
        }

        $account = $users = $accounts = $avatar = null;
        $this->getGlobals($request, $account, $users, $accounts, $avatar);

        $viewBy = $request->input('vb', 'w');
        $user_count = count($users);

        if ($user_count > 1 || ($user_count == 1 && $viewBy == 'w')) {
            session(['viewby' => 'w']);
            return $this->viewWeekSchedule($account, $users,
                $start_date->startOfWeek(), $this->getCurWeek(),
                $accounts, $avatar, $user_count == 1);
        } else {
            session(['viewby' => 'm']);
            return $this->viewMonthSchedule($start_date, $account, $users, $accounts, $avatar, $user_count == 1);
        }
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
            ->route('orders.show', ['id' => $id, 'aid' => $aid, 'vb' => session('viewby')])
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
            ->route('orders.show', ['id' => $thedateYmd, 'aid' => $aid, 'vb' => session('viewby')]);
//            ->withFlashSuccess('Order saved.');
    }
}
