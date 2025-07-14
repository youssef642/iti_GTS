<?php
namespace App\Http\Controllers\api\auth;

use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Rules\UniqueEmailAcrossTables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class StudentAuthController extends Controller
{
    public function register(Request $request)

    {
        $request->validate([
            'name' => 'required|string',
            'email' => ['required', 'email', 'unique:students', new UniqueEmailAcrossTables('student')],
            'password' => 'required|min:6|confirmed',
            'university' => 'nullable|string',
            'faculty' => 'nullable|string',
            'gender' => 'nullable|string',
            'age' => 'nullable|integer',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'track_name' => 'nullable|string',
            'interests' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'name',
            'email',
            'university',
            'faculty',
            'gender',
            'age',
            'phone',
            'address',
            'track_name',
            'interests'
        ]);

        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('students', 'public');
        }

        $student = Student::create($data);

        $token = $student->createToken('student_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Student registered successfully',
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $student = Student::where('email', $request->email)->first();

        if ($student && Hash::check($request->password, $student->password)) {
            $token = $student->createToken('student_token')->plainTextToken;
            $user_type = 'student';
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'user_type' => $user_type,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        $company = Company::where('email', $request->email)->first();

        if ($company && Hash::check($request->password, $company->password)) {
            $token = $company->createToken('company_token')->plainTextToken;
            $user_type = 'company';
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'user_type' => $user_type,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

       return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 401);
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
