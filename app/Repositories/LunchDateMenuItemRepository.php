<?php

namespace App\Repositories;

use App\Models\LunchDateMenuItem;

/**
 * Class LunchDateMenuItemRepository.
 */
class LunchDateMenuItemRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = LunchDateMenuItem::class;

    /**
     * @param integer $lunchdate_id
     * @return mixed
     */
    public function getForScheduling($lunchdate_id)
    {
        return $this->query()
            ->select('menuitem_id as id', 'item_name', 'price')
            ->join('los_menuitems as mi', 'mi.id', '=', 'menuitem_id')
            ->where('lunchdate_id', $lunchdate_id)
            ->where('active', 1)
            ->orderBy('item_name')
            ->get();
    }

    /**
     * @param integer $lunchdate_id
     * @param array $excludeids
     * @return mixed
     */
    public function getForOrder($lunchdate_id, $excludeids)
    {
        $q = $this->query()
            ->select('menuitem_id as id', 'description', 'price')
            ->join('los_menuitems as mi', 'mi.id', '=', 'menuitem_id')
            ->where('lunchdate_id', $lunchdate_id)
            ->where('active', 1)
            ->orderBy('item_name');

        if ($excludeids)
            $q->whereNotIn('mi.id', $excludeids);

        return $q->get();
    }

}
