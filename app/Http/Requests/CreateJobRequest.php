<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'location' => 'nullable|string',
            'type' => 'required|string',
            'is_remote' => 'boolean',
            'experience' => 'nullable|string',
            'published' => 'boolean',
        ];
    }
}
