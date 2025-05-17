<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
    public function rules(Request $request): array
    {
        $rules =  [
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['required', 'email', 'lowercase', 'max:255'],
            'aadhaar_number' => ['required', 'numeric', 'digits:12'],
            'mobile_number' => ['required', 'numeric', 'digits:10', 'regex:/^[6-9]\d{9}$/'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'role' => ['sometimes', 'in:admin,super-admin'],
            "profile-pic" => $request->isPrecognitive() ? [] : ["nullable", "max:5120", "mimes:jpeg,jpg,png"],
        ];

        $user = $request->route('user');

        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            array_push($rules['email'], 'unique:users,email,' . $user->id);
            array_push($rules['aadhaar_number'], 'unique:users,aadhaar_number,' . $user->id);
            array_push($rules['mobile_number'], 'unique:users,mobile_number,' . $user->id);
            unset($rules['password']);
        } else {
            array_push($rules['email'], 'unique:' . User::class);
            array_push($rules['aadhaar_number'], 'unique:' . User::class);
            array_push($rules['mobile_number'], 'unique:' . User::class);
        }

        return $rules;
    }
}
