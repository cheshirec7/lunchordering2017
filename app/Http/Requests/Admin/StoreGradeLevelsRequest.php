<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;


class StoreGradeLevelsRequest extends FormRequest
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
            'grade' => 'required|max:10|unique:los_gradelevels,grade',
            'grade_desc' => 'required|max:50|unique:los_gradelevels,grade_desc',
            'report_order' => 'required|integer|min:1|unique:los_gradelevels,report_order',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'grade_desc.unique' => 'Please enter a unique grade description.',
        ];
    }
}
