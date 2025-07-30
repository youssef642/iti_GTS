<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\JobPost;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6'
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Admin created successfully', 'admin' => $admin], 201);
    }

    // 🗑️ 2. حذف Admin
    public function destroy($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $admin->delete();
        return response()->json(['message' => 'Admin deleted successfully']);
    }

    // 📋 3. عرض كل الـ Admins
    public function index()
    {
        $admins = Admin::all();
        return response()->json([
            'count' => $admins->count(),
            'admins' => $admins
        ]);
    }
    public function getalljobs()
    {
        $jobs = JobPost::orderBy('created_at', 'desc')->with('company')->withCount('jobApplications')->get();
        return response()->json([
            'count' => $jobs->count(),
            'jobs' => $jobs
        ]);
    }

    public function deleteStudent($id)
    {
        $student = \App\Models\Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }
     public function deleteCompany($id)
    {
        $company = \App\Models\Company::find($id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $company->delete();
        return response()->json(['message' => 'Company deleted successfully']);
    }
    public function deleteJob($id){
        $job = JobPost::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
    public function deleteApplication($id){
        $application = JobApplication::find($id);
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $application->delete();
        return response()->json(['message' => 'Application deleted successfully']);

    }
}