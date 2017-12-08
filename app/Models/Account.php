<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Silber\Bouncer\Database\Role;

class Account extends Authenticatable
{
    use Notifiable;
    use HasRolesAndAbilities;


    protected $table = 'los_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_name', 'email', 'active', 'password', 'allow_new_orders', 'confirmed_credits', 'confirmed_debits'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'assigned_roles', 'entity_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'account_id');
    }

    public function getIsAdminAttribute()
    {
        return Auth::id() == 1;
    }

    public function getEditButtonAttribute()
    {

        return '<a href="' . route('admin.accounts.edit', $this) . '" class="btn btn-sm btn-edit"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
    }

    public function getUserButtonAttribute()
    {
        $route = route('admin.users.index') . '?aid=' . $this->id;
        return '<a href="' . $route . '" class="btn btn-sm btn-warning"><i class="fa fa-user" data-toggle="tooltip" data-placement="top" title="Attached Users"></i></a>';
    }

    public function getDeleteButtonAttribute()
    {
        if ($this->id != Auth::id() && $this->id != 1) {
            return '<a href="' . route('admin.accounts.destroy', $this) . '"
                 data-method="delete"  
                 data-trans-title="Are you sure you want to delete account:"     
                 data-trans-text="' . $this->account_name . '"
                 class="btn btn-sm btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
        }

        return '';
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getPictureAttribute($size = false)
    {
        if (!$size) {
            $size = config('gravatar.default.size');
        }
        return \Gravatar::get($this->email, ['size' => $size]);
    }

    public function getActionButtonsAttribute()
    {
        if ($this->users->count() == 0)
            return $this->edit_button . '&nbsp;' . $this->user_button . '&nbsp;' . $this->delete_button;

        return $this->edit_button . '&nbsp;' . $this->user_button;
    }
}
