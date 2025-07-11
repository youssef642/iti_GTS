<?php
namespace App\Http\Controllers\api\auth;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class StudentAuthController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:students',
        'password' => 'required|min:6|confirmed',
        'faculty' => 'nullable|string',
        'university' => 'nullable|string',
        'track' => 'nullable|string',
        'image' => 'nullable|string',  
        'company_id' => 'nullable|exists:companies,id',
    ]);

    $student = Student::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'faculty' => $request->faculty,
        'university' => $request->university,
        'track' => $request->track,
        'image' => $request->image,
        'company_id' => $request->company_id,
    ]);

    $token = $student->createToken('student_token')->plainTextToken;

    return response()->json([
        'status' => true,
        'message' => 'Student registered successfully',
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
}


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = Student::where('email', $request->email)->first();

        if (! $student || ! Hash::check($request->password, $student->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $student->createToken('student_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
