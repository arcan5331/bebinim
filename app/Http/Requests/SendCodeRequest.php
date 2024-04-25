<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => ['required','regex:/^(\+98|0)?9\d{9}$/']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
