<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                'title' => 'required|string|max:255|unique:categories',
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            'PUT', 'PATCH' => [
                'title' => 'sometimes|required|string|max:255|unique:categories',
                'file' => 'sometimes|required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],
            'GET' => [
                'title' => 'sometimes|string|max:255',
                'sort_search' => 'required|string|in:sort, search',
                'sort_order' => 'sometimes|in:asc,desc',
            ]
        };
    }
}
