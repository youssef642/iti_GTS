<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
public function authorize()
{
return true;
}

public function rules()
{
return [
    'name' => 'nullable|string|max:255',
    'email' => 'nullable|email|max:255',
    'phone' => 'nullable|string|max:15',
    'address' => 'nullable|string|max:255',
    'specialization' => 'nullable|string|max:255',
    'image' => 'nullable',
    'cover_image' => 'nullable',
    'type' => 'nullable|string|max:255',
    'team_size' => 'nullable|string|max:255',
    'founded' => 'nullable|max:255',
    'country' => 'nullable|string|max:255',
    'instagram' => 'nullable|url|max:255',
    'about' => 'nullable|string|max:1000',
    'linkedin' => 'nullable|url|max:255',
    'website' => 'nullable|url|max:255',
    'facebook' => 'nullable|url|max:255',
];
}
}