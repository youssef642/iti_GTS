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

    public function company_jobs()
    {
        $jobs = JobPost::where('company_id', Auth::id())->get();
        return JobPostResource::collection($jobs);
    }

    public function create_job(CreateJobRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = Auth::id();

        $job = JobPost::create($data);

        return response()->json([
            'message' => 'Job created successfully',
            'data' => new JobPostResource($job)
        ], 201);
    }

    public function update_job(UpdateJobRequest $request, $jobId)
    {
        $job = JobPost::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->fill($request->validated());
        $job->save();

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => new JobPostResource($job)
        ]);
    }

    public function job_applications($jobId)
    {
        $job = JobPost::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $applications = JobApplication::with('user')->where('job_id', $jobId)->get();

        return JobApplicationResource::collection($applications);
    }
}
