<?php

namespace App\Http\Requests;

use App\Rules\GetRule;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;

class DishRequest extends FormRequest
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
        return match ($this->method()) {
            'POST' => [
                'title' => 'required|string|max:255|unique:dishes',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'compound' => 'required|string',
                'calories' => 'required|numeric',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id'
            ],
            'GET' => [
                'sort' => 'sometimes|string|in:title,compound,price,calories',
                'sort_order' => 'sometimes|string|in:asc,desc',
                'search' => 'sometimes|string|in:compound,title',
                'search_order' => ['bail','sometimes','string', new GetRule('App\Models\Dish')
                    ],
                'page' => 'sometimes|integer',
                'perPage' => 'sometimes|integer',
            ],
            default => throw new InvalidArgumentException("Invalid request method [{$this->method()}]")
        };
    }
}
