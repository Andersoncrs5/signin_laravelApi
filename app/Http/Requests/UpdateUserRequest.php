<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Validation rules for the registration form.
     */
    public function rules(): array
    {
        return [
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
