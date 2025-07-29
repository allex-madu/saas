<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'nif' => 'sometimes|nullable|string|max:20',
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'city_id' => 'sometimes|nullable|exists:cities,id',

            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],

            'password' => 'nullable|string|min:6',

            'roles' => 'sometimes|array',
            'roles.*' => 'integer|exists:roles,id',
        ];
    }
}
