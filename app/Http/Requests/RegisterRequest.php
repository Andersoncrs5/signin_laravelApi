<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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

    /**
     * Validation rules for the registration form.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150', 'min:10'],
            'name' => ['required', 'alpha', 'max:50', 'min:2'],
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

            'name.required' => 'Name is required.',
            'name.alpha' => 'Name must contain only letters.',
            'name.max' => 'Name cannot exceed 50 characters.',
            'name.min' => 'Name must be at least 2 characters long.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 50 characters.',
        ];
    }

    /**
     * Return a JSON response on validation failure.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
