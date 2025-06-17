<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DishRequest extends FormRequest
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
                'title' => 'required|string|max:255|unique:dishes',
                'price' => 'required|decimal',
                'calories' => 'required|integer',
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            'PUT', 'PATCH' => [
                'title' => 'required|string|max:255|unique:dishes',
                'price' => 'required|decimal',
                'calories' => 'required|integer',
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'compound' => 'sometimes|text',
            ],
            'GET' => [
                'title' => 'sometimes|string|max:255',
                'compound' => 'sometimes|text',
                'price' => 'sometimes|decimal',
                'calories' => 'sometimes|integer',
                'sort_order' => 'sometimes|in:asc,desc',
            ]
        };
    }
}
