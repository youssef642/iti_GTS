<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
    
    
}
