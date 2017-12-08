<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NoLunchException extends Model
{

    protected $table = 'los_nolunchexceptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason', 'description', 'grade_id', 'teacher_id', 'exception_date'
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
        'exception_date'
    ];

    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.nolunchexceptions.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        return '<a href="' . route('admin.nolunchexceptions.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete lunch exception:"     
                 data-trans-text="' . $this->reason . ' on ' . $this->exception_date->toDateString() . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
    }

    public function getActionButtonsAttribute()
    {
        if ($this->exception_date->gt(Carbon::today())) {
            if ($this->orders_placed)
                return $this->edit_button;

            return $this->edit_button . '&nbsp;' . $this->delete_button;
        }
        return '';
    }
}
