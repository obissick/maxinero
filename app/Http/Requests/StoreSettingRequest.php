<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_name'     => ['required', 'string', 'max:255'],
            'api_url'      => ['required', 'url'],
            'api_username' => ['required', 'string', 'max:255'],
            'api_password' => ['required', 'string'],
        ];
    }
}
