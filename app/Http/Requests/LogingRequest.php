<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LogingRequest extends FormRequest
{
    public function authorize(): bool 
    {
        if (session('active')) {
            throw new HttpResponseException(response()->json([
                'message' => 'You are already logged in.',
            ], 400));
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150', 'min:10'],
            'password' => ['required', 'string', 'min:8', 'max:50'],
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 150 characters.',
            'email.min' => 'Email must be at least 10 characters long.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 50 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
