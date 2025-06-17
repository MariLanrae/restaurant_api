<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
                'number' => 'required|string',
                'closing_date' => 'sometimes|date',
                'user_id' => 'required|unsigned bigint|exists:users,id',
                'status' => 'required|in:closed,open, canceled, paid',
                'created_at' => 'required|date',
            ],
            'PUT', 'PATCH' => [
                'creation_date' => 'sometimes|date',
                'user_id' => 'sometimes|unsigned bigint|exists:users,id',
                'closing_date' => 'sometimes|date',
                'status' => 'sometimes|in:open,closed,canceled, paid',
            ],
            'GET' => [
                'number' => 'sometimes|string',
                'closing_date' => 'sometimes|date',
                'user_id' => 'sometimes|unsigned bigint|exists:users,id',
                'sort_order' => 'sometimes|in:asc,desc',
            ]
        };
    }
}
