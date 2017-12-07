<?php

namespace App\Repositories;

use App\Models\Provider;
use Illuminate\Support\Facades\DB;

/**
 * Class ProviderRepository.
 */
class ProviderRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Provider::class;

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->select(['los_providers.id', 'provider_name', 'provider_image', 'provider_url', 'allow_orders', 'provider_includes', DB::raw('coalesce(sum(qty),0) as numorders')])
            ->leftJoin('los_orderdetails as od', 'od.provider_id', '=', 'los_providers.id')
            ->where('los_providers.id', '>', config('app.provider_lunchprovided_id'))
            ->groupBy('los_providers.id')
            ->groupBy('provider_name')
            ->groupBy('provider_image')
            ->groupBy('provider_url')
            ->groupBy('allow_orders')
            ->groupBy('provider_includes');
    }

    /**
     * @return array
     */
    public function getForSelect($userCreatedOnly, $addSelect = false, $activeOnly = false)
    {
        $q = $this->query()
            ->select('id', 'provider_name')
            ->orderBy('provider_name')
            ->where('id', '>', config('app.provider_lunchprovided_id'));

        if ($activeOnly) {
            $q->where('allow_orders', 1);
        }

        $arr = $q
            ->get()
            ->pluck('provider_name', 'id')
            ->all();

        if (!$userCreatedOnly) {
            $arr = array('1' => 'No Lunch (No School)', '2' => 'No Lunch (Early Dismissal)', '3' => 'Lunch Provided') + $arr;
        }

        if ($addSelect) {
            $arr = array('' => '- Select -') + $arr;
        }

        return $arr;
    }

    /**
     * @return array
     */
    public function getForSelectForProvider($pid)
    {
        return $this->query()
            ->select('id', 'provider_name')
            ->where('id', $pid)
            ->get()
            ->pluck('provider_name', 'id')
            ->all();
    }

    public function providerForDate($provide_date)
    {
        return $this->query()
            ->join('los_lunchdates as ld', 'ld.provider_id', '=', 'los_providers.id')
            ->where('provide_date', $provide_date)
            ->first();
    }

}
