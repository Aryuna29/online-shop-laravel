<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'phone' => 'required|min:9|max:255',
            'comment' => 'nullable|max:255',
            'address' => 'required|min:2',
        ];
    }
}
