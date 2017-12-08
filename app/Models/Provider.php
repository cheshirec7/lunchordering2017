<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'los_providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_name', 'provider_image', 'provider_url', 'allow_orders', 'provider_includes'
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
        return '<a href="' . route('admin.providers.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getMenuItemButtonAttribute()
    {
        $route = route('admin.menuitems.index') . '?pid=' . $this->id;
        return '<a href="' . $route . '" class="btn btn-sm btn-warning"><i class="fa fa-utensil-spoon" data-toggle="tooltip" data-placement="top" title="Attached Menu Items"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        return '<a href="' . route('admin.providers.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete provider:"     
                 data-trans-text="' . $this->provider_name . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
    }

    public function getActionButtonsAttribute()
    {
        if ($this->numorders > 0)
            return $this->edit_button . '&nbsp;' . $this->menu_item_button;
        return $this->edit_button . '&nbsp;' . $this->menu_item_button . '&nbsp;' . $this->delete_button;
    }
}
