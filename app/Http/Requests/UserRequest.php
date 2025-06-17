<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()){
            'POST' => [
                'name' => 'sometimes|string',
                'email' => 'sometimes|email|unique:users',
                'role_id' => 'sometimes|exists:roles,id',
                'password' => 'sometimes|string',
                'pincode' => 'sometimes|char|unique:users',
            ],
            'PUT', 'PATCH' => [
                'name' => 'sometimes|string',
                'email' => 'sometimes|email|unique:users',
                'role_id' => 'sometimes|exists:roles,id',
            ],
            'GET' => [
                'name' => 'sometimes|string',
                'email' => 'sometimes|email',
                'role_id' => 'sometimes|exists:roles,id',
                'sort_order' => 'sometimes|in:asc,desc',
            ]
        };
    }
}
