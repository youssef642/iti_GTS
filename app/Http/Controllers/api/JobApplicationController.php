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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    public function studentApply(ApplyJobRequest $request, $jobId)
    {
        try {
            $student = Auth::user();
            
            // Find the job and check if it exists
            $job = JobPost::findOrFail($jobId);
            
            // Check if job is still active and accepting applications
            if ($job->status !== 'active' ) {
                return response()->json([
                    'message' => 'This job is not currently accepting applications.'
                ], 400);
            }
            
            
            // Check if student has already applied
            $existingApplication = JobApplication::where('student_id', $student->id)
                ->where('job_post_id', $job->id)
                ->first();
                
            if ($existingApplication) {
                return response()->json([
                    'message' => 'You have already applied to this job.',
                    'application_id' => $existingApplication->id
                ], 409);
            }
            
            // Validate and process the request data
            $data = $request->validated();
            
            // Handle CV file upload with proper validation
            if ($request->hasFile('cv')) {
                $cvFile = $request->file('cv');
                
                // Validate file type
                $allowedTypes = ['pdf', 'doc', 'docx'];
                $fileExtension = strtolower($cvFile->getClientOriginalExtension());
                
                if (!in_array($fileExtension, $allowedTypes)) {
                    return response()->json([
                        'error' => 'CV must be a PDF, DOC, or DOCX file.'
                    ], 422);
                }
                
                // Validate file size (max 5MB)
                if ($cvFile->getSize() > 5 * 1024 * 1024) {
                    return response()->json([
                        'error' => 'CV file size must be less than 5MB.'
                    ], 422);
                }
                
                // Store file with unique name
                $cvPath = $cvFile->store('cvs', 'public');
                $data['cv'] = $cvPath;
            } else {
                return response()->json([
                    'error' => 'CV file is required.'
                ], 422);
            }
            
            // Set application data
            $data['student_id'] = $student->id;
            $data['job_post_id'] = $job->id;
            
            // Create application within transaction
            DB::beginTransaction();
            
            try {
                $application = JobApplication::create($data);
                
                // Update job application count if needed
                // $job->increment('application_count');
                
                DB::commit();
                
                return response()->json([
                    'message' => 'Application submitted successfully.',
                    'application' => $application,
                    'job_title' => $job->title,
                    'company_name' => $job->company->name ?? 'Unknown Company'
                ], 201);
                
            } catch (\Exception $e) {
                DB::rollBack();
                
                // Clean up uploaded file if database operation fails
                if (isset($data['cv']) && Storage::disk('public')->exists($data['cv'])) {
                    Storage::disk('public')->delete($data['cv']);
                }
                
                Log::error('Job application creation failed: ' . $e->getMessage());
                
                return response()->json([
                    'error' => 'Failed to submit application. Please try again.'
                ], 500);
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Job not found.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Job application error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
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

        $applications = JobApplication::with('student', 'jobPost.company')
        ->where('job_post_id', $jobId)
        ->get();
        $applications = JobApplication::with('student','jobPost')->where('job_post_id', $jobId)->get();



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
  public function getApplication($applicationId){
    $application = JobApplication::where('id', $applicationId)->with('jobPost')->first();
    if (!$application) {
        return response()->json(['message' => 'Application not found'], 404);
    }
    return response()->json($application);
}

public function getAllApplications()
{
    $applications = JobApplication::orderBy('created_at', 'desc')->with('student', 'jobPost')->get();
    $count = $applications->count();
    return response()->json([
        'count' => $count,
        'applications' => $applications
    ]);
}
}
