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


class JobPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }

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

        $job = JobPost::create($validated);

        return response()->json(['message' => 'Job created successfully', 'data' => $job], 201);
    }
    


    
    public function company_jobs()
    {
        $jobs = JobPost::where('company_id', Auth::id())->get();
        return JobPostResource::collection($jobs);
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

   
    public function destroy($id)
    {
        $job = JobPost::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully'], 200);
    }
}
