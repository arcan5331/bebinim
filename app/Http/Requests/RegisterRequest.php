<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'regex:/^(\+98|0)?9\d{9}$/', Rule::unique(User::class)],
            'code' => ['required', 'integer', 'numeric', 'digits:4'],
            'password' => ['required', Password::min(8)],
            'name' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
