<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LunchDate extends Model
{
    protected $table = 'los_lunchdates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id', 'provide_date', 'orders_placed', 'additional_text', 'extended_care_text'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'provide_date',
        'orders_placed'
    ];
}
