<?php
namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;

class JobPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }

    // جلب كل الوظائف
    public function index()
    {
        return JobPost::with('company')->get();
    }

    // جلب وظيفة واحدة بالتفصيل
    public function show($id)
    {
        $job = JobPost::with('company')->find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return $job;
    }

    // نشر وظيفة جديدة (من قبل شركة)
    public function store(Request $request)
    {
        $company_id=auth()->user->id;
        $validated = $request->validate([
            'company_id'=>$company_id,
            'title' => 'required|s`tring|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'location' => 'nullable|string',
            'type' => 'required|string',
            'is_remote' => 'boolean',
            'experience' => 'nullable|string',
            'published' => 'boolean',
        ]);

        $job = JobPost::create($validated);

        return response()->json(['message' => 'Job created successfully', 'data' => $job], 201);
    }
}
