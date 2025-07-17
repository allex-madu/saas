<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'sometimes|array',
            'roles.*' => 'integer|exists:roles,id',
        ];
       
    }
}
