<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentsRequest extends FormRequest
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
            'pay_method' => 'required|integer|between:1,4',
            'credit_desc' => 'max:100',
            'credit_date' => 'required|date',
            'credit_amt' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'pay_method.between' => 'Please select a payment method.',
            'credit_amt.min' => 'The payment amount must be greater than $0.00.',
        ];
    }
}
