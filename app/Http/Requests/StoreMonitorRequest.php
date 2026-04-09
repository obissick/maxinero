<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monitor_id'       => ['required', 'string', 'max:255'],
            'monitor_type'     => ['required', 'string', 'max:255'],
            'module'           => ['required', 'string', 'max:255'],
            'user'             => ['required', 'string', 'max:255'],
            'password'         => ['required', 'string'],
            'monitor_interval' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
