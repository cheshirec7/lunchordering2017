<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class User extends Model
{
    protected $table = 'los_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'last_name', 'first_name', 'grade_id', 'teacher_id', 'user_type', 'allowed_to_order',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name'];


    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->last_name . ', ' . $this->first_name;
    }

    /**
     * @return string
     */
    public function getFirstLastAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }


    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.users.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        if ($this->account_id != Auth::id()) {
            return '<a href="' . route('admin.users.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete user:"     
                 data-trans-text="' . $this->full_name . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
        }
        return '';
    }

    public function getActionButtonsAttribute()
    {
        if ($this->numorders > 0)
            return $this->edit_button;
        return $this->edit_button . '&nbsp;' . $this->delete_button;
    }
}
