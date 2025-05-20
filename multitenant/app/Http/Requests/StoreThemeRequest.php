<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreThemeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can add authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'sidebar_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'text_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'font_family' => 'required|string|in:Arial,Segoe UI,Roboto,Open Sans,Helvetica Neue,sans-serif',
            'navbar_style' => 'required|string|in:light,dark,transparent',
            'card_style' => 'required|string|in:default,outlined,elevated'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'primary_color.regex' => 'The primary color must be a valid hex color code (e.g., #000000)',
            'secondary_color.regex' => 'The secondary color must be a valid hex color code (e.g., #000000)',
            'sidebar_color.regex' => 'The sidebar color must be a valid hex color code (e.g., #000000)',
            'text_color.regex' => 'The text color must be a valid hex color code (e.g., #000000)',
            'font_family.in' => 'Please select a valid font family',
            'navbar_style.in' => 'Please select a valid navbar style',
            'card_style.in' => 'Please select a valid card style'
        ];
    }
}
