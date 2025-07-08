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
use App\Models\JobPost;
use App\Models\JobApplication;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::find(Auth::id());
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

        $company->fill($request->validated());
        $company->save();

        return response()->json([
            'message' => 'Company updated successfully',
            'company' => new CompanyResource($company),
        ]);
    }
   

   
}
