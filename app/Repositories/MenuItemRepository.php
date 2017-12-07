<?php

namespace App\Repositories;

use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

/**
 * Class MenuItemRepository.
 */
class MenuItemRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = MenuItem::class;

    /**
     * @param integer $provider_id
     * @return mixed
     */
    public function getForDataTable($provider_id)
    {
        return $this->query()
            ->select('los_menuitems.id', 'item_name', 'description', 'los_menuitems.price', 'active',
                DB::raw('count(qty) as numorders'))
            ->leftJoin('los_orderdetails as od', 'od.menuitem_id', '=', 'los_menuitems.id')
            ->where('los_menuitems.provider_id', $provider_id)
            ->orderBy('item_name')
            ->groupBy('los_menuitems.id')
            ->groupBy('item_name')
            ->groupBy('description')
            ->groupBy('los_menuitems.price')
            ->groupBy('active');
    }

    /**
     * @param integer $provider_id
     * @return mixed
     */
    public function getForScheduling($provider_id, $excludeids)
    {
        $q = $this->query()
            ->select('id', 'item_name', 'description', 'price')
            ->where('provider_id', $provider_id)
            ->where('active', 1)
            ->orderBy('item_name');

        if ($excludeids) {
            $q->whereNotIn('id', $excludeids);
        }

        return $q->get();
    }

    /**
     * @param boolean $addSelectText
     * @return array
     */
    public function getForSelect($addSelectText = false)
    {
        $arr = $this->query()
            ->select('id', 'item_name')
            ->orderBy('item_name')
            ->get()
            ->pluck('item_name', 'id')
            ->all();

        if ($addSelectText)
            return array('' => '- Select -') + $arr;

        return $arr;
    }
}
