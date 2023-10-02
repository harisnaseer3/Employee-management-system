<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'], //, 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[^a-zA-Z0-9]/'
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'The password format is invalid. It should contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];
    }
}
