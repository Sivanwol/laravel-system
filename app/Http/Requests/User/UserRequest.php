<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[\+]?[1-9][\d]{0,15}$/',
                Rule::unique('users')->ignore($userId),
            ],
            'country_code' => ['required', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'phone.regex' => 'Please enter a valid phone number.',
            'phone.unique' => 'This phone number is already taken.',
        ];
    }

}
