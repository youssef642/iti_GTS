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


    

    // public function getEducation()
    // {
    //     $student = Student::where('id', Auth::id())->first();

    //     $education = json_decode($student->education, true);
    //     return response()->json($education);
    // }

}
