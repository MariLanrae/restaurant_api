<?php

namespace App\Http\Requests;

use App\Rules\GetRule;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;

class OrderRequest extends FormRequest
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
                /*'number' => 'sometimes|string',
                'closing_date' => 'sometimes|date',
                'user_id' => 'sometimes|int|exists:users,id',
                'sort_order' => 'sometimes|in:asc,desc',
                'sort' => 'sometimes|in:number,closing_date,user_id',
                'page' => 'sometimes|integer',
                'perPage' => 'sometimes|integer',*/
                'sort' => 'sometimes|string|in:number,closing_date,user_id',
                'sort_order' => 'sometimes|string|in:asc,desc',
                'search' => 'sometimes|string|in:number,closing_date,user_id',
                'search_order' => ['bail','sometimes','string', new GetRule('App\Models\Order')
                ],
                'page' => 'sometimes|integer',
                'perPage' => 'sometimes|integer',
            ],
            'POST' => [
                'number' => 'required|string|min:3',
                'user_id' => 'required|int|exists:users,id',
                'status' => 'required|in:closed,open, canceled, paid',
                'dishes' => 'required|array',
                'dishes.*.title' => 'required|string|exists:dishes,title',
                'dishes.*.quantity' => 'required|integer|min:1',
            ],
            'PUT' => [
                'creation_date' => 'sometimes|date',
                'user_id' => 'sometimes|int|exists:users,id',
                'closing_date' => 'sometimes|date',
                'status' => 'sometimes|string|in:open,closed,canceled, paid',
            ],
            default => throw new InvalidArgumentException("Invalid request method [{$this->method()}]")
        };
    }
}
