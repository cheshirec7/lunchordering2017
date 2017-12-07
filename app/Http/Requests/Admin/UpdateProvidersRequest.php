<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProvidersRequest extends FormRequest
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
        $provider = $this->route('provider');
        return [
            'provider_name' => 'required|max:50|unique:los_providers,provider_name,' . $provider,
            'provider_image' => 'required|max:50',
            'provider_url' => 'required|url',
            'allow_orders' => 'required|boolean',
            'provider_includes' => 'max:191',
        ];
    }
}
