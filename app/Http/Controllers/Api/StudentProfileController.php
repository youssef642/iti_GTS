<?php

namespace App\Http\Controllers\Api;
use App\Models\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Student::where('id', Auth::id())->first();
      
            return response()->json($student);
        
    }
    public function update(Request $request)
    {
        $student = Student::where('id', Auth::id())->first();
        if ($student) {
            $student->update($request->all());
            return response()->json(['message' => 'Profile updated successfully']);
        }
        return response()->json(['message' => 'Student not found'], 404);
    }
    

    public function getAllStudents()
    {
        $students = Student::all();
        $count = $students->count();
    
        return response()->json([
            'count' => $count,
            'students' => $students
        ]);
    }

}
