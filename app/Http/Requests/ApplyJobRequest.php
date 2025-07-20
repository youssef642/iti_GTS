<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
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
            'cover_letter' => 'required|string|min:10|max:2000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'cover_letter.required' => 'A cover letter is required.',
            'cover_letter.min' => 'Cover letter must be at least 10 characters long.',
            'cover_letter.max' => 'Cover letter cannot exceed 2000 characters.',
            'cv.required' => 'A CV file is required.',
            'cv.file' => 'CV must be a valid file.',
            'cv.mimes' => 'CV must be a PDF, DOC, or DOCX file.',
            'cv.max' => 'CV file size must be less than 5MB.',
        ];
    }
}
