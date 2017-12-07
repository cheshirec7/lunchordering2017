<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemsRequest extends FormRequest
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
            'item_name' => 'required|max:50',
            'description' => 'required|max:191',
            'price' => 'required|numeric|min:0.01|max:' . config('app.menuitem_max_price'),
            'active' => 'required|boolean',
        ];
    }

}
