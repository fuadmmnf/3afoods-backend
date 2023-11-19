<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string',
            'desc' => 'required|string',
            'type' => 'required|in:retail,wholesale',
            'img' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Example: Max size is 2MB and allowed types are jpeg, png, jpg, gif
            'price' => 'nullable|numeric',
            'unit' => 'required|string',
        ];
    }
}
