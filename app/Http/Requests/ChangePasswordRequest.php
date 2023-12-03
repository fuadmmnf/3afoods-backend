<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = Auth::id();
        return [
            'old_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($userId) {
                    // Check if the provided old password matches the user's current password
                    $user = User::find($userId);
                    if (!Hash::check($value, $user->password)) {
                        $fail(__('Invalid old password'));
                    }
                },
            ],
            'new_password' => 'required|min:6',
        ];
    }
}
