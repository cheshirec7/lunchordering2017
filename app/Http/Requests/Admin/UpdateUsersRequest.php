<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
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
            'last_name' => 'required|max:191',
            'first_name' => 'required|max:191',
            'allowed_to_order' => 'required',
            'user_type' => 'required',
            'grade_id' => 'required'
//            'teacher_id' => 'required'
        ];
    }
}
