<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
        $userId = Auth::id();

        $rules = [
            'name' => 'string|required',
            'email' => 'email|unique:users,email,' . $userId,
            'phone' => 'string|required',
            'usertype' => 'string|in:retail,wholesale,ship_supply',
        ];

        // If the request is for updating, include the current password validation
        if ($this->isMethod('patch')) {
            $rules['current_password'] = [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, auth()->user()->password)) {
                        $fail(__('The current password is incorrect.'));
                    }
                },
            ];
        } else {
            // Additional rules for user registration
            $rules += [
                'name' => 'string|required',
                'company_name' => 'nullable|string',
                'avn' => 'nullable|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'phone' => 'string|required',
                'usertype' => 'required|string|in:retail,wholesale,ship_supply',
            ];
        }

        return $rules;
    }
}
