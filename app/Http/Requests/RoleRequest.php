<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class RoleRequest extends FormRequest
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
            'GET' => [
                'page' => 'sometimes|integer',
                'perPage' => 'sometimes|integer',
            ],
            'POST', => [
                'name' => 'required|string|max:255|unique:roles',
            ],
            'PUT' => [
                'name' => ['sometimes','string','max:255', Rule::unique('roles')->ignore($this->role->id)],
            ],
            default => throw new InvalidArgumentException("Invalid request method [{$this->method()}]")
        };
    }
}
