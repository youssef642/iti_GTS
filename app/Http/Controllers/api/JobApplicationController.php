<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyJobRequest;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusChanged;
use App\Http\Resources\JobApplicationResource;

class JobApplicationController extends Controller
{
    public function studentApply(ApplyJobRequest $request, $jobId)
    {
        $student = Auth::user();

        $job = JobPost::findOrFail($jobId);


        $exists = JobApplication::where('student_id', $student->id)
            ->where('job_post_id', $job->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You have already applied to this job.'
            ], 409);
        }


        $data = $request->validated();

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs');
            $data['cv'] = $cvPath;
        } else {
            return response()->json(['error' => 'CV file not received'], 422);
        }

        $data['student_id'] = $student->id;
        $data['job_post_id'] = $job->id;

        $application = JobApplication::create($data);


        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => $application
        ], 201);
    }


    // تحديث حالة طلب التقديم (قبول/رفض) وإرسال إيميل للطالب
    public function updateStatus(Request $request, $applicationId)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $application = JobApplication::findOrFail($applicationId);
        $application->status = $request->status;
        $application->save();

        Mail::to($application->student->email)->send(new ApplicationStatusChanged($application));


        return response()->json([
            'message' => 'Application status updated and email sent.',
            'application' => $application,
        ]);
    }

    public function getStudentApplications()
{
    $student = Auth::user();

    $applications = JobApplication::with('jobPost.Company')  
        ->where('student_id', $student->id)
        ->get();

    return response()->json($applications);
}
   public function company_job_applications($jobId)
{
    $job = JobPost::find($jobId);
    if (!$job) {
        return response()->json(['message' => 'Job not found'], 404);
    }

    public function company_job_applications($jobId)
    {
        $job = JobPost::find($jobId);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $applications = JobApplication::with('student','jobPost')->where('job_post_id', $jobId)->get();

    $applications = JobApplication::with('student', 'jobPost.company')
        ->where('job_post_id', $jobId)
        ->get();

    return JobApplicationResource::collection($applications);
}
public function cancelApplication($applicationId)
{
    $application = JobApplication::find($applicationId);
    if (!$application) {
        return response()->json(['message' => 'Application not found'], 404);
    }
    $application->delete();

    return response()->json("Application cancelled successfully");
}
}
