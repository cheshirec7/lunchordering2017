<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_name' => 'required|max:191',
            'email' => 'required|email|unique:los_accounts,email,' . $this->route('account'),
            'role_id' => 'required',
            'active' => 'required',
            'allow_new_orders' => 'required'
        ];
    }
}
