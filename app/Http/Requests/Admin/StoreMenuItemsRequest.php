<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemsRequest extends FormRequest
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
            'provider_id' => 'required|integer|min:1',
            'item_name' => 'required|max:50',
            'description' => 'required|max:191',
            'price' => 'required|numeric|min:0.01|max:' . config('app.menuitem_max_price'),
            'active' => 'required|boolean',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'provider_id.min' => 'Please assign a provider to this menu item.',
            'provider_id.required' => 'Please assign a provider to this menu item.',
        ];
    }
}
