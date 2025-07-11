<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyJobRequest;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\JobApplicationResource;

class JobApplicationController extends Controller
{
    public function studentApply(ApplyJobRequest $request, $jobId)
    {
        $student = Auth::user();

        $job = JobPost::findOrFail($jobId);

        $exists = JobApplication::where('student_id', $student->id)
                                ->where('job_id', $job->id)
                                ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You have already applied to this job.'
            ], 409);
        }
        $data = $request->validated();
        $data['student_id'] = $student->id;
        $data['job_id'] = $job->id;
        

        $application = JobApplication::create($data);

        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => $application
        ], 201);
    }
    public function getStudentApplications()
    {
        $student = Auth::user();

        $applications = JobApplication::where('student_id', $student->id)->get();

        return response()->json($applications);
    }
    public function company_job_applications($jobId)
    {
        $job = JobPost::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $applications = JobApplication::with('user')->where('job_id', $jobId)->get();

        return JobApplicationResource::collection($applications);
    }
}
