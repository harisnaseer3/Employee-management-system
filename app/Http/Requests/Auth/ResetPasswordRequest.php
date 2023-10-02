<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email','exists:users'],
            'password' => ['required', 'string', 'min:6', 'max:255','confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[^a-zA-Z0-9]/'],
            'token' => ['required', 'string'],
        ];
    }


    public function messages()
    {
        return [
            'password.regex' => 'The password format is invalid. It should contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];
    }
}
