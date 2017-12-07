<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //just return true since Gate is checked for route
//        return Gate::allows('manage-backend');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_name' => 'required|max:50',
            'first_names' => 'required|max:50',
            'email' => 'required|email|unique:los_accounts,email',
            'role_id' => 'required',
            'active' => 'required',
            'allow_new_orders' => 'required'
        ];
    }
}
