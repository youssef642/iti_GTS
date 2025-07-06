<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;



class CompanyController extends Controller
{
    
    public function index()
    {
        $company = Company::find(Auth::id());
        return response()->json($company);
    }

   
    public function update(Request $request)
    {
        $company = Company::find(Auth::id());
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
        
        ]);

        $company->fill($data);
        $company->save();

        return response()->json(['message' => 'Company updated successfully', 'company' => $company]);
    }

    public function company_jobs(){
        $jobs = JobPost::where('company_id', Auth::id())->get();
        return response()->json($jobs);
    }

    public function create_job(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'location' => 'nullable|string',
            'type' => 'required|string',
            'is_remote' => 'boolean',
            'experience' => 'nullable|string',
            'published' => 'boolean',
        ]);

        $validated['company_id'] = Auth::id();

        $job = JobPost::create($validated);

        return response()->json(['message' => 'Job created successfully', 'data' => $job], 201);
    }

   
    public function update_job(Request $request, $jobId)
    {
        $job = JobPost::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'location' => 'nullable|string',
            'type' => 'required|string',
            'is_remote' => 'boolean',
            'published' => 'boolean',
        ]);

        $job->fill($data);
        $job->save();

        return response()->json(['message' => 'Job updated successfully', 'job' => $job]);
    }


    public function job_applications($jobId)
    {
        $job = JobPost::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $applications = JobApplication::with('user')->where('job_id', $jobId)->get();
        return response()->json($applications);
    }


}
