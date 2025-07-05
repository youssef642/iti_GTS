<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function apply(Request $request, $jobId)
    {
        $student = Auth::user();

        // تأكد إن الوظيفة موجودة
        $job = JobPost::findOrFail($jobId);

        // تحقق إن الطالب لم يقدم بالفعل
        $exists = JobApplication::where('student_id', $student->id)
                                ->where('job_post_id', $job->id)
                                ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You have already applied to this job.'
            ], 409);
        }

        // التقديم
        $application = JobApplication::create([
            'student_id' => $student->id,
            'job_post_id' => $job->id,
            'cover_letter' => $request->input('cover_letter'),
        ]);

        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => $application
        ], 201);
    }
}
