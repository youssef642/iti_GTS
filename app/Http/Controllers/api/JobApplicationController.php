<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusChanged;

class JobApplicationController extends Controller
{
    // تقديم طلب لوظيفة مع رفع CV
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

        // تحقق بيانات الريكوست، مع التحقق من ملف الـ CV
        $request->validate([
            'cover_letter' => 'nullable|string',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',  // السماح برفع ملفات pdf و word حتى 2 ميجا
        ]);

        // رفع ملف الـ CV لو موجود
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        // إنشاء الابليكشن
        $application = JobApplication::create([
            'student_id' => $student->id,
            'job_post_id' => $job->id,
            'cover_letter' => $request->input('cover_letter'),
            'cv_path' => $cvPath,
            'status' => 'pending', // الحالة الافتراضية
        ]);

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

        // إرسال الإيميل للطالب
        Mail::to($application->student->email)->send(new ApplicationStatusChanged($application));

        return response()->json([
            'message' => 'Application status updated and email sent.',
            'application' => $application,
        ]);
    }
}
