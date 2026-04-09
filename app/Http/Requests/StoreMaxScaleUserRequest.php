<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaxScaleUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'  => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'account'  => ['required', 'string', 'in:admin,basic'],
        ];
    }
}
