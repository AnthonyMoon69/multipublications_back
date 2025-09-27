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
            'image' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'name' => ['prohibited'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $hasImage = $this->has('image');

                if (! $this->filled('email') && ! $this->filled('password') && ! $hasImage) {
                    $validator->errors()->add('email', 'Debe proporcionar al menos el correo electrónico, la contraseña o la imagen.');
                }
            },
        ];
    }
}
