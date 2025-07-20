<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Http\Resources\JobPostResource;
use App\Http\Resources\JobApplicationResource;
use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use Illuminate\Support\Facades\Log;


class JobPostController extends Controller
{
  

    public function student_index()
    {
        $jobs = JobPost::with('company')->get();
        return JobPostResource::collection($jobs);
    }

    public function show($id)
    {
        $job = JobPost::with('company')->find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return new JobPostResource($job);
    }

    public function storejob(CreateJobRequest $request)
    {

        $company_id = auth::user()->id;
        $validated = $request->validated();
        $validated['company_id'] = $company_id;

        $validated['company_id'] = $company_id;

        $job = JobPost::create($validated);

        return response()->json([
            'message' => 'Job created successfully',
            'data' => $job
        ], 201);
    }
    


    

    public function company_jobs()
    {
        $total_applications = JobApplication::whereHas('jobPost', function ($query) {
            $query->where('company_id', Auth::id());
        })->count();
        $jobs = JobPost::withCount('jobApplications')->where('company_id', Auth::id())->get();

        return response()->json([
            'jobs' => JobPostResource::collection($jobs),
            'total_applications' => $total_applications
        ]);
    }


    public function update_job(UpdateJobRequest $request, $jobId)
    {
        
       $job = JobPost::find($jobId);

    if (!$job) {
        return response()->json(['message' => 'Job not found'], 404);
    }

    // تحقق إن الشركة المالكة هي اللي بتعدل
    if ($job->company_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $job->fill($request->validated());
    $job->save();

    return response()->json([
        'message' => 'Job updated successfully',
        'job' => new JobPostResource($job)
    ]);
    }

   
    public function destroy($id)
    {
        $job = JobPost::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully'], 200);
    }
    public function statistics(){
        $company_id = Auth::id();
        $total_jobs = JobPost::where('company_id', $company_id)->count();
        $total_applications = JobApplication::whereHas('jobPost', function ($query) use ($company_id) {
            $query->where('company_id', $company_id);
        })->count();
            return response()->json([
            'total_jobs' => $total_jobs,
            'total_applications' => $total_applications
        ]);
    }
    public function completeApplication($id)
    {
        $application = JobApplication::where('student_id', Auth::id())->where('id', $id)->first();
        $job=JobPost::where('id', $application->job_post_id)->first();
        $job->status = 'completed';
        $job->save();
        return response()->json(['message' => 'Application completed successfully'], 200);
    }
}
