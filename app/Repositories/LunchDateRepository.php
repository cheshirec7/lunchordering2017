<?php

namespace App\Repositories;

use App\Models\LunchDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class LunchDateRepository.
 */
class LunchDateRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = LunchDate::class;

    /**
     * @return mixed
     */
    public function getForScheduleMonth(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select('los_lunchdates.id AS lunchdate_id', 'lp.id AS prov_id', 'provider_name', 'provider_image',
                'provide_date', 'allow_orders', 'orders_placed', 'additional_text', 'extended_care_text', 'provider_url')
            ->join('los_providers as lp', 'lp.id', '=', 'los_lunchdates.provider_id')
            ->whereBetween('provide_date', array($start_date, $end_date))
            ->orderBy('provide_date')
            ->get();
    }

    public function getForOrders(Carbon $start_date, Carbon $end_date)
    {
        return $this->query()
            ->select('los_lunchdates.id AS lunchdate_id', 'lp.id AS prov_id', 'provider_name', 'provider_image',
                'provide_date', 'allow_orders', 'orders_placed', 'additional_text', 'extended_care_text', 'provider_url')
            ->join('los_providers as lp', 'lp.id', '=', 'los_lunchdates.provider_id')
            ->whereBetween('provide_date', array($start_date, $end_date))
            ->orderBy('provide_date')
            ->get();
    }

    public function getForOrder(Carbon $date)
    {
        return $this->query()
            ->select('los_lunchdates.id AS lunchdate_id', 'provider_id', 'provider_name', 'provider_image', 'provider_includes',
                'provide_date', 'allow_orders', 'orders_placed', 'additional_text', 'extended_care_text', 'provider_url')
            ->join('los_providers as p', 'p.id', '=', 'los_lunchdates.provider_id')
            ->where('provide_date', $date)
            ->first();
    }

    public function ordersPlaced($date)
    {
        $ld = $this->query()
            ->where('provide_date', $date)
            ->first();

        return $ld && $ld->orders_placed;
    }

    public function getForReport(Carbon $date)
    {
        return $this->query()
            ->select(DB::raw("DATE_FORMAT(provide_date,'%W, %M %D, %Y') AS lunch_date"), 'provider_name', 'provide_date', 'additional_text', 'extended_care_text')
            ->join('los_providers as lp', 'lp.id', '=', 'los_lunchdates.provider_id')
            ->where('provide_date', ">=", $date)
            ->orderBy('provide_date')
            ->get();
    }

    public function getForSelectDatesWithOrders()
    {
        return array('0' => '- Select -') +
            $this->query()
                ->select('provide_date as thedate', DB::raw("DATE_FORMAT(provide_date,'%W, %M %D, %Y') AS prov_date"))
                ->whereRaw('provide_date IN (SELECT DISTINCT order_date FROM los_orders)')
                ->orderBy('provide_date', 'desc')
                ->get()
                ->pluck('prov_date', 'thedate')
                ->all();
    }

    public function datesWithOrders()
    {
        //select provide_date,orders_placed,DATEDIFF(provide_date,curdate()) AS daysfromtoday from los_lunchdates where provide_date IN (SELECT DISTINCT order_date FROM los_orders) order by provide_date desc
        return $this->query()
            ->select('id as lunchdate_id', 'provide_date', 'orders_placed', DB::raw('DATEDIFF(provide_date,curdate()) AS daysfromtoday'))
            ->whereRaw('provide_date IN (SELECT DISTINCT order_date FROM los_orders)')
            ->orderBy('provide_date', 'desc')
            ->get();
    }

    public function updateOrdersPlaced()
    {
        $affected = DB::update('UPDATE los_lunchdates SET orders_placed=now() WHERE provide_date < now() AND orders_placed IS NULL');
    }

}
