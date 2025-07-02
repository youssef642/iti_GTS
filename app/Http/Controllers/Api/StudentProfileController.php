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
        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }else {
            return response()->json($student);
        }

    }


    // public function storeexperience(Request $request)
    // {
    //     $student = Student::where('user_id', Auth::id())->first();
    //     if (!$student) {
    //         return response()->json(['message' => 'Student profile not found'], 404);
    //     }

    //     $data = $request->validate([
    //         'experience' => 'required|array',
    //         'experience.*.job_title' => 'required|string|max:255',
    //         'experience.*.company' => 'required|string|max:255',
    //         'experience.*.start_date' => 'required|date',
    //         'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
    //     ]);

    //     $student->experience = json_encode($data['experience']);
    //     $student->save();

    //     return response()->json(['message' => 'Experience updated successfully', 'student' => $student]);
    // }


    public function storeEducation(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }

        $data = $request->validate([
            'education' => 'required|string',
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $student->education = json_encode($data);
        $student->save();

        return response()->json(['message' => 'Education updated successfully', 'student' => $student]);
    }
    public function getEducation()
    {
        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }

        $education = json_decode($student->education, true);
        return response()->json($education);
    }

}
