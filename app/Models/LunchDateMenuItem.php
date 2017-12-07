<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LunchDateMenuItem extends Model
{

    protected $table = 'los_lunchdate_menuitems';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lunchdate_id', 'menuitem_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
