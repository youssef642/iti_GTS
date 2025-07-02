<?php

namespace App\Http\Controllers\Api;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::where('student_id', Auth::id())->get();
        return response()->json($skills);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $skill = new Skill();
        $skill->name = $data['name'];
        $skill->student_id = Auth::id();
        $skill->save();

        return response()->json(['message' => 'Skill created successfully', 'skill' => $skill], 201);
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill = Skill::find($id);
        if (!$skill || $skill->student_id !== Auth::id()) {
            return response()->json(['message' => 'Skill not found or unauthorized'], 404);
        }

        $skill->delete();
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
