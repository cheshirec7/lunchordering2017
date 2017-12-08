<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    protected $table = 'los_menuitems';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id', 'item_name', 'description', 'price', 'active'
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
        return '<a href="' . route('admin.menuitems.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        return '<a href="' . route('admin.menuitems.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete menu item:"     
                 data-trans-text="' . $this->item_name . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
    }

    public function getActionButtonsAttribute()
    {
        if ($this->numorders > 0)
            return $this->edit_button;
        return $this->edit_button . '&nbsp;' . $this->delete_button;
    }
}
