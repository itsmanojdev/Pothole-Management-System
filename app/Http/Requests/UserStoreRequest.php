<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['required', 'email', 'lowercase', 'max:255', 'unique:' . User::class],
            'aadhaar_number' => ['required', 'numeric', 'digits:12', 'unique:' . User::class],
            'mobile_number' => ['required', 'numeric', 'digits:10', 'regex:/^[6-9]\d{9}$/', 'unique:' . User::class],
            'password' => ['required', Password::defaults(), 'confirmed']
        ];
    }
}
