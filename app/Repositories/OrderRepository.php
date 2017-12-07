<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderRepository.
 */
class OrderRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Order::class;

    /**
     * @return mixed
     */
    public function getForOrders(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select('id AS order_id', 'user_id', 'short_desc', 'order_date', 'total_price', 'status_code')
            ->whereBetween('order_date', array($start_date, $end_date))
            ->orderBy('order_date')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getForReport(Carbon $date)
    {
        return $this->query()
            ->select('order_date', 'first_name', 'last_name', 'short_desc', 'status_code')
            ->join('los_users as u', 'u.id', '=', 'los_orders.user_id')
            ->where('los_orders.account_id', Auth::id())
            ->where('order_date', ">=", $date)
            ->orderBy('order_date')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getOrder($user_id, Carbon $order_date)
    {
        return $this->query()
            ->where('order_date', $order_date)
            ->where('user_id', $user_id)
            ->first();
    }

    /**
     * @return void
     */
    public function updateDescAndTotalPrice($order_id)
    {
        $update = 'update los_orders ';
        $update .= ' set total_price=(select coalesce(sum(price),0) from los_orderdetails where order_id=:oid1)';
        $update .= ' where id=:oid2';
        DB::statement($update, array('oid1' => $order_id, 'oid2' => $order_id));

        // this won't give us the qtys...
//        $update = 'update los_orders ';
//        $update .= ' set short_desc=';
//        $update .= " (SELECT GROUP_CONCAT(item_name SEPARATOR ', ')";
//        $update .= ' FROM los_menuitems mi';
//        $update .= ' inner join los_orderdetails od on od.menuitem_id=mi.id';
//        $update .= ' where od.order_id=:oid1)';
//        $update .= ' where id=:oid2';
//        DB::statement($update, array('oid1' => $order_id, 'oid2' => $order_id));

        // this will give us the qtys
        $items = DB::table('los_orderdetails as od')
            ->select('item_name', 'qty')
            ->join('los_menuitems as mi', 'mi.id', '=', 'od.menuitem_id')
            ->where('od.order_id', $order_id)
            ->get();

        $short_desc = '';
        foreach ($items as $item) {
            if ($item->qty > 1)
                $short_desc .= '(' . $item->qty . 'x) ' . $item->item_name . ', ';
            else
                $short_desc .= $item->item_name . ', ';
        }

        $this->query()
            ->where('id', $order_id)
            ->update(['short_desc' => substr($short_desc, 0, -2)]);
    }


    /**
     * @return mixed
     */
    public function getOrderCountsForRange(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select(DB::raw('COUNT(id) as order_count'), 'order_date')
            ->whereBetween('order_date', array($start_date, $end_date))
            ->groupBy('order_date')
            ->get();
    }

    /**
     * @return integer
     */
    public function getOrderCountForDate(Carbon $order_date)
    {
        return $this->query()
            ->where('order_date', $order_date)
            ->count();
    }

    /**
     * @return integer
     */
    public function numOrdersDateGrade(Carbon $order_date, $grade_id)
    {
        return $this->query()
            ->join('los_users as u', 'u.id', '=', 'user_id')
            ->where('order_date', $order_date)
            ->where('grade_id', $grade_id)
            ->count();
    }

    public function myAccountAggregates($account_id)
    {
        // select count(los_orders.id) as order_count, COALESCE(sum(total_price),0) as total_price, first_name, last_name, user_id
        // from los_orders
        // inner join los_users as u on u.id = los_orders.user_id
        // where los_orders.account_id = 4
        // group by user_id,first_name,last_name
        // order by first_name asc
        return $this->query()
            ->select(DB::raw('count(los_orders.id) as order_count'),
                DB::raw('coalesce(sum(total_price),0) as total_price'), 'first_name', 'last_name', 'user_id')
            ->join('los_users as u', 'u.id', '=', 'los_orders.user_id')
            ->where('los_orders.account_id', $account_id)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->groupBy('user_id')
            ->groupBy('first_name')
            ->groupBy('last_name')
            ->get();
    }

    public function datatableForUserID($user_id)
    {
        return $this->query()
            ->select('id', 'order_date', 'short_desc', 'total_price')
            ->where('user_id', $user_id)
            ->orderBy('order_date');
    }

    public function adminProviderReport($order_date)
    {
        return $this->query()
            ->select(DB::raw('coalesce(sum(qty),0) AS qty'), 'description')
            ->join('los_orderdetails as od', 'od.order_id', '=', 'los_orders.id')
            ->join('los_menuitems as mi', 'mi.id', '=', 'od.menuitem_id')
            ->where('los_orders.order_date', $order_date)
            ->groupBy('description')
            ->get();
    }

    public function adminOrdersByGradeReport($order_date)
    {
        return $this->query()
            ->select('first_name', 'last_name', 'short_desc', 'grade_desc')
            ->join('los_users as u', 'u.id', '=', 'los_orders.user_id')
            ->join('los_gradelevels as gl', 'gl.id', '=', 'u.grade_id')
            ->where('order_date', $order_date)
            ->orderBy('report_order')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function adminLunchLabels($order_date)
    {
        return $this->query()
            ->select('first_name', 'last_name', 'short_desc', 'grade_desc')
            ->join('los_users as u', 'u.id', '=', 'los_orders.user_id')
            ->join('los_gradelevels as gl', 'gl.id', '=', 'u.grade_id')
            ->where('order_date', $order_date)
            ->orderBy('report_order')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    /**
     * Get all of the orders for a lunch date.
     *
     * @param  $id (accountID)
     */
    public function getDatatable($id)
    {
        //DB::raw('CONCAT(u2.last_name,", ",u2.first_name," / ",grade_desc) as teacher_name_grade'),
        /*
         select los_orders.id as DT_RowId,CONCAT(u1.last_name,", ",u1.first_name) as name, short_desc,total_price,grade_desc,
        u1.teacher_id as teacher_id,status_code,notes,status_code as status_code_int,los_orders.user_id
        from los_orders
            inner join los_users as u1 on u1.id = los_orders.user_id
            inner join los_gradelevels as gl on gl.id = u1.grade_id
            where lunchdate_id = 48
          */
        return $this->query()
            ->select('los_orders.id', 'last_name', 'first_name', 'short_desc',
                'total_price', 'grade_desc', 'status_code', 'los_orders.user_id', 'u.grade_id')
            ->join('los_users as u', 'u.id', '=', 'los_orders.user_id')
            ->join('los_gradelevels as gl', 'gl.id', '=', 'u.grade_id')
            ->where('lunchdate_id', $id)
            ->orderBy('last_name')
            ->orderBy('first_name');
    }

    /**
     * Get all of the orders for a lunch date.
     *
     * @param  $id (accountID)
     */
    public function updateStatuses()
    {
        $affected = DB::update('UPDATE los_orders SET status_code=1 WHERE status_code=0 AND order_date IN (SELECT provide_date FROM los_lunchdates WHERE orders_placed IS NOT NULL)');
        $affected = DB::update('UPDATE los_orders SET status_code=0 WHERE status_code=1 AND order_date IN (SELECT provide_date FROM los_lunchdates WHERE orders_placed IS NULL)');
    }
}
