<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{

    protected $table = 'los_gradelevels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grade', 'grade_desc', 'report_order'
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
        return '<a href="' . route('admin.gradelevels.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        return '<a href="' . route('admin.gradelevels.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete grade level:"     
                 data-trans-text="' . $this->grade_desc . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
    }

    public function getActionButtonsAttribute()
    {
        if ($this->numusers > 0)
            return $this->edit_button;
        return $this->edit_button . '&nbsp;' . $this->delete_button;
    }
}
