<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users',
            'psw' => 'required|min:6',
            'psw-repeat' => 'required|confirmed:psw',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'имя должно быть указано',
            'name.min' => 'имя должно быть больше 2',
            'name.regex' => 'имя должно состоять из букв',
            'name.max' => 'имя должно быть меньше 255',
            'email.required' => 'email должен быть заполнен',
            'email.email' => 'неверный формат email',
            'email.max' => 'превышен максимальный лимит',
            'email.unique' => 'такой email уже существует',
            'psw.required' => 'не заполнено',
            'psw.min' => 'пароль должен быть больше 6 символов',
            'psw-repeat.required' => 'не заполнено',
            'psw-repeat.confirmed' => 'пароли не совпадают',
        ];
    }
}
