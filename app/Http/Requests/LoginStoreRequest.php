<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class LoginStoreRequest extends FormRequest
{

    public string $loginField;

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
        if (is_numeric($request->primary)) {
            if (strlen((string)$request->primary) <= 10) {
                $this->loginField = 'mobile_number';
                $primary_validation = ['primary' => ['required', 'digits:10', 'regex:/^[6-9]\d{9}$/']];
            } else {
                $this->loginField = 'aadhaar_number';
                $primary_validation = ['primary' => ['required', 'digits:12']];
            }
        } else {
            $this->loginField = 'email';
            $primary_validation = ['primary' => ['required', 'email']];
        }

        return array_merge($primary_validation, [
            'password' => ['required']
        ]);
    }

    /**
     * Custom Messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'primary.required' => 'This field is required.',
            'primary.email' => 'Enter valid email address.',
            'primary.digits' => 'Enter valid mobile number(10 digits) or aadhaar number(12 digits).',
            'primary.regex' => 'The mobile number given is invalid.'
        ];
    }

    /**
     * passedValidation - primary field handle
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $this->merge([$this->loginField => request('primary')]);
        $this->offsetUnset('primary');
    }
}
