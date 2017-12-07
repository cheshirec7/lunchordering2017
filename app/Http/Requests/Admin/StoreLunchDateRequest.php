<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLunchDateRequest extends FormRequest
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
            'provide_date' => 'required',
            'provider_id' => 'required',
            'additional_text' => 'max:50',
            'extended_care_text' => 'max:50',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'provider_id.required' => 'Please select a provider.',
        ];
    }
}
