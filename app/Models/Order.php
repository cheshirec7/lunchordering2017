<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'los_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'user_id', 'lunchdate_id', 'order_date', 'short_desc',
        'total_price', 'status_code', 'entered_by_account_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'order_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.ordermaint.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getActionButtonsAttribute()
    {
        return $this->edit_button;
    }

}
