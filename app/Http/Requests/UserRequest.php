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
        return true;
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
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'role_id' => 'required|exists:roles,id',
                'password' => 'required|string',
                'pincode' => 'required|string|size:4|unique:users',
            ],
            'PUT' => [
                'name' => 'sometimes|string',
                'email'=>'sometimes|email|max:255|unique:users,email',
                'role_id' => 'sometimes|exists:roles,id',
            ],
            'GET' => [
                'sort' => 'sometimes|string|in:name,role_id',
                'search' => 'sometimes|string|in:name,email,role_id',
                'sort_order' => 'sometimes|string|in:asc,desc',
                'search_order' => 'sometimes|string',
            ]
        };
    }
}
