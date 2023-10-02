<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contact_number' => 'nullable|string',
        ];

        if ($this->getMethod() == 'POST') {
            $rules['email'] = 'required|email|unique:employees,email';
        }

        if ($this->getMethod() == 'PUT' || $this->getMethod() == 'PATCH') {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('employees')->ignore($this->route('employee')),  // assuming 'id' is the URL parameter
            ];
        }

        return $rules;
    }

}
