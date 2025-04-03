<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => 'required|min:2|regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'current_password' => [
                'nullable',
                'string',
                // проверка: текущий пароль совпадает с паролем пользователя
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Текущий пароль неверен.');
                    }
                }
            ],
            'password' => 'nullable|string|min:6|confirmed'
            ];
    }

}
