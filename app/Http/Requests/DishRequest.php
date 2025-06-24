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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->route()->getActionMethod()){
            'update' => [
                'title' => 'sometimes|required|string|max:255|unique:dishes',
                'price' => 'sometimes|required|decimal',
                'calories' => 'sometimes|required|integer',
                'file' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'compound' => 'sometimes|string',
            ],
            'store' => [
                'title' => 'required|string|max:255|unique:dishes',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'compound' => 'required|string',
                'calories' => 'required|numeric',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id'
            ],
            'index' => [
                'title' => 'sometimes|string|max:255',
                'compound' => 'sometimes|string',
                'sort' => 'sometimes|in:title,compound,price,calories',
                'sort_order' => 'sometimes|in:asc,desc',
            ]
        };
    }
}
