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
            'img' => 'required|string', // You may need to handle file uploads appropriately
            'price' => 'nullable|numeric',
            'unit' => 'required|string',
        ];
    }
}
