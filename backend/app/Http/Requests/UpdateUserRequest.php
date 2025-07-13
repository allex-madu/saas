<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'sometimes|required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:6',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ];
    }
}
