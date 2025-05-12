<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PotholeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => ["required", "string", "min:3", "max:255"],
            "description" => ["nullable", "string"],
            "address" => ["required", "string"],
            "latitude" => ["required", "numeric", "between:-90,90"],
            "longitude" => ["required", "numeric", "between:-180,180"],
            "pothole-image" => $this->isPrecognitive() ? [] : ["nullable", "mimes:jpeg,jpg,png"],
        ];
    }

    /**
     * failedValidation - If error in latitude or longitude, add address error
     *
     * @param  ValidationValidator $validator
     * @return void
     */
    protected function failedValidation(ValidationValidator $validator)
    {
        $errors = $validator->errors();

        if ($errors->has('latitude') || $errors->has('longitude')) {
            $errors->forget('latitude');
            $errors->forget('longitude');

            $errors->add('address', "Something went wrong. Please select address again");
        }
    }

}
