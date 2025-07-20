<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\JobPostResource;
use App\Http\Resources\JobApplicationResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::where('id',Auth::id())->first();
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return new CompanyResource($company);
    }
    public function get_company_by_id($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request)
    {
        $company = Company::find(Auth::id());
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $data = $request->validated();
        // Handle file uploads
        if ($request->hasFile('image')) {
            try {
                $data['image'] = $request->file('image')->store('company','public');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Image upload failed: ' . $e->getMessage()], 500);
            }
        }

        if ($request->hasFile('cover_image')) {
            try {
                $data['cover_image'] = $request->file('cover_image')->store('company','public');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Cover image upload failed: ' . $e->getMessage()], 500);
            }
        }
        
        try {
            $company->fill($data);
            $company->save();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save company data: ' . $e->getMessage()], 500);
        }

        $response = [
            'message' => 'Company updated successfully',
            'company' => new CompanyResource($company),
            
        ];
        
        return response()->json($response);
    }
}
