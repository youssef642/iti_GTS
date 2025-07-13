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
'name' => 'required|string|max:255',
'email' => 'required|email|max:255',
'phone' => 'required|string|max:15',
'address' => 'required|string|max:255',
'description' => 'nullable|string|max:1000',
'specialization' => 'nullable|string|max:255',
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
'type' => 'nullable|string|max:255',
'team_size' => 'nullable|integer|min:1',
'founded' => 'nullable|date',
'instagram' => 'nullable|url|max:255',
'about' => 'nullable|string|max:1000',

'linkedin' => 'nullable|url|max:255',
'website' => 'nullable|url|max:255',
'facebook' => 'nullable|url|max:255',
];
}
}