<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsersRequest extends FormRequest
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
            'account_id' => 'required|integer|min:1',
            'last_name' => 'required|max:191',
            'first_name' => 'required|max:191',
            'allowed_to_order' => 'required',
            'user_type' => 'required',
            'grade_id' => 'required'
//            'teacher_id' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'account_id.min' => 'Please select an Account.',
        ];
    }
}
