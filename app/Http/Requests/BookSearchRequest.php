<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users (or add logic if needed)
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'isbn' => 'required|regex:/^\d{10,13}$/',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'isbn.required' => 'Please enter an ISBN.',
            'isbn.regex' => 'Invalid ISBN format. Please enter a 10 or 13-digit ISBN.',
        ];
    }
}