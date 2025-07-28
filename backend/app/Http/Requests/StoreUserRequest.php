<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'nif' => 'required|string|max:20|unique:people,nif',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'active' => 'required|boolean',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'required|array',
            'roles.*' => 'integer|exists:roles,id',
        ];
    }
}
