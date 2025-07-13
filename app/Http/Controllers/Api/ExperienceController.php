<?php

namespace App\Http\Controllers\Api;
use App\Models\Experience;
use App\Http\Controllers\Controller;
use App\Http\Requests\storeExperienceRequest;
use App\Http\Requests\updateExperienceRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function getExperience()
    {
        $experience = Experience::where('student_id', Auth::id())->get();

        return response()->json($experience);
    }

    public function storeExperience(storeExperienceRequest $request)
    {
        $data = $request->validated();
        $data['student_id'] = Auth::id();
        $experience = Experience::create($data);

        return response()->json(['message' => 'Experience added successfully', 'data' => $experience], 201);
    }

    public function updateExperience(updateExperienceRequest $request, $id)
    {
        $experience = Experience::findOrFail($id);
        if ($experience->student_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $data = $request->validated();
        $experience->update($data);
        return response()->json(['message' => 'Experience updated successfully', 'data' => $experience]);
    }
    public function destroyExperience($id)
    {
        $experience = Experience::findOrFail($id);
        if ($experience->student_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $experience->delete();

        return response()->json(['message' => 'Experience deleted successfully']);
    }

}
