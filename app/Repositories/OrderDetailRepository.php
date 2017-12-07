<?php

namespace App\Repositories;

use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderDetailRepository.
 */
class OrderDetailRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = OrderDetail::class;

    /**
     * @return mixed
     */
    public function getForOrder($order_id)
    {
        return $this->query()
            ->select('menuitem_id', 'description', 'mi.price', 'qty')
            ->join('los_menuitems as mi', 'mi.id', '=', 'menuitem_id')
            ->where('order_id', $order_id)
            ->where('active', 1)
            ->orderBy('item_name')
            ->get();
    }

    /**
     * @return void
     */
    public function updateProvidersAndPrices($order_id)
    {
        $update = 'update los_orderdetails od, los_menuitems mi';
        $update .= ' set od.price=mi.price, od.provider_id=mi.provider_id';
        $update .= ' where od.menuitem_id = mi.id';
        $update .= ' and order_id=:oid';
        DB::statement($update, array('oid' => $order_id));
    }

    public function adminAccountDetailReport($account_id)
    {
        return $this->query()
            ->select('first_name', 'last_name', 'order_date', 'item_name', 'qty', 'status_code', 'los_orderdetails.price')
            ->join('los_orders as o', 'o.id', '=', 'los_orderdetails.order_id')
            ->join('los_users as u', 'u.id', '=', 'o.user_id')
            ->join('los_menuitems as mi', 'mi.id', '=', 'los_orderdetails.menuitem_id')
            ->where('los_orderdetails.account_id', $account_id)
            ->orderBy('o.order_date')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }
}
