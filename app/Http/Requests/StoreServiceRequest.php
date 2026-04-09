<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id'   => ['required', 'string', 'max:255'],
            'service_type' => ['required', 'string', 'max:255'],
            'module'       => ['required', 'string', 'max:255'],
            'user'         => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string'],
        ];
    }
}
