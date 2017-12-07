<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LunchDate;
use App\Repositories\LunchDateRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderMaintController extends Controller
{
    protected $orders;
    protected $lunchdates;
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  OrderRepository $orders
     * @param  LunchDateRepository $lunchdates
     * @param  UserRepository $users
     */
    public function __construct(OrderRepository $orders,
                                LunchDateRepository $lunchdates,
                                UserRepository $users)
    {
        $this->orders = $orders;
        $this->lunchdates = $lunchdates;
        $this->users = $users;
    }

    /**
     *
     */
    public function index()
    {
        $this->lunchdates->updateOrdersPlaced();
        $this->orders->updateStatuses();
        $dates = $this->lunchdates->datesWithOrders();
        $options = [];
        foreach ($dates as $date) {
            $s = ' data-daysfromtoday="' . $date->daysfromtoday . '" ';
            $s .= 'value="' . $date->lunchdate_id . '">';
            $s .= $date->provide_date->format('l, F jS, Y');

            if ($date->orders_placed) {
                $options[] = '<option data-locked=1' . $s . '</option>';
            } else {
                $options[] = '<option data-locked=0' . $s . ' (Ordering Open)</option>';
            }
        }

        return view('admin.ordermaint.index')
            ->withOptions($options);
    }

    /**
     * Get the Order Maintenance datatable
     */
    public function show($id)
    {
        return Datatables::of($this->orders->getDatatable($id))
            ->escapeColumns(['grade_desc', 'short_desc'])
//            ->addColumn('actions', function ($order) {
//                return $order->action_buttons;
//            })
            ->editColumn('grade_desc', function ($order) {
                if ($order->grade_id == 1)
                    return '(n/a)';
                else
                    return $order->grade_desc;
            })
            ->editColumn('total_price', function ($order) {
                return money_format('$%.2n', $order->total_price / 100);
            })
            ->editColumn('status_code', function ($order) {
                if ($order->status_code == 1) // app.status_code_locked
                    return "<span class='locked'>-&nbsp;Locked&nbsp;-</span>";
                return '';
            })
            ->addColumn('name', function ($order) {
                return $order->last_name . ', ' . $order->first_name;
            })
            ->removeColumn('user_id')
            ->removeColumn('grade_id')
            ->removeColumn('first_name')
            ->removeColumn('last_name')
            ->make(true);
    }

    /**
     *
     */
    public function postLunchDateLockToggle(Request $request)
    {
        $ld = LunchDate::find($request->input('ldid', 0));
        if ($ld) {
            $locked = 0;
            if ($ld->orders_placed) {
                $ld->orders_placed = NULL;
            } else {
                $ld->orders_placed = Carbon::now();
                $locked = 1;
            }
            if ($ld->save()) {
                $this->orders->updateStatuses();
                return response()->json(array('error' => false, 'locked' => $locked));
            }
            return response()->json(array('error' => true, 'msg' => 'Unable to update lunch date.'));
        }
        return response()->json(array('error' => true, 'msg' => 'Lunch date not found.'));
    }
}
