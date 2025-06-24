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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->route()->getActionMethod()) {
            'store' => [
                'title' => 'required|string|max:255|unique:categories',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            'update' => [
                'title' => 'sometimes|string|max:255|unique:categories',
                'file' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            'index' => [
                'title' => 'sometimes|string|max:255',
                'sort_search' => 'sometimes|string|in:search',
                'sort_order' => 'sometimes|in:asc,desc',
            ]
        };
    }
}
