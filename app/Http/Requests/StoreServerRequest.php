<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'server_id' => ['required', 'string', 'max:255'],
            'address'   => ['required', 'string', 'max:255'],
            'port'      => ['required', 'integer', 'min:1', 'max:65535'],
            'protocol'  => ['required', 'string', 'max:255'],
        ];
    }
}
