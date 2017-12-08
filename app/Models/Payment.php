<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'los_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'pay_method', 'credit_amt', 'fee', 'credit_date', 'credit_desc'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'credit_date'
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
        return '<a href="' . route('admin.payments.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        $text = money_format('$%.2n', $this->credit_amt / 100);
        $text .= ' on ';
        $text .= $this->credit_date->toDateString();

        return '<a href="' . route('admin.payments.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete payment:"     
                 data-trans-text="' . $text . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
    }

    public function getActionButtonsAttribute()
    {
        return
            $this->edit_button . '&nbsp;' .
            $this->delete_button;
    }
}
