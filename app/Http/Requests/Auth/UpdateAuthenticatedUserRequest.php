<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class UpdateAuthenticatedUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['sometimes', 'required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()?->id)],
            'password' => ['sometimes', 'required', 'confirmed', Password::defaults()],
            'name' => ['prohibited'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->filled('email') && ! $this->filled('password')) {
                    $validator->errors()->add('email', 'Debe proporcionar al menos el correo electrónico o la contraseña.');
                }
            },
        ];
    }
}
