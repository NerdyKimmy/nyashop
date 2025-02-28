<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old_password' => 'required|string',
            // Пароль має бути не менше 8 символів, містити лише англійські букви та цифри, а також підтвердження
            'new_password' => ['required', 'min:8', 'regex:/^[a-zA-Z0-9]+$/', 'confirmed'],
        ];
    }

    public function messages()
    {
        // Забираємо всі стандартні повідомлення і повертаємо лише одне повідомлення про помилку,
        // яке стосується мінімальної довжини (якщо пароль не пройде перевірку по мінімуму або за шаблоном)
        return [
            'new_password.min'       => 'The password must be at least 8 characters.',
            'new_password.regex'     => 'The password must be at least 8 characters.',
            'new_password.confirmed' => 'The password must be at least 8 characters.',
        ];
    }
}
