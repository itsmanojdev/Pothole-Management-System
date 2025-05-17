<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(User $user): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::id() == $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isProfile = request()->routeIs('user.profile.*');
        $newPassword = ['required', Password::defaults(), 'confirmed:new_password_confirmation'];
        $isProfile && array_push($newPassword, 'different:password');

        return [
            'password' => ['required', 'current_password'],
            'new_password' => $newPassword
        ];
    }
}
