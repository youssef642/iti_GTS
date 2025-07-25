<?php



namespace App\Http\Controllers\api\auth;

use App\Models\Company;
use App\Rules\UniqueEmailAcrossTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CompanyAuthController extends Controller
{
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => ['required', 'email', 'unique:companies', new UniqueEmailAcrossTables('company')],
        'password' => 'required|min:6|confirmed',
        'phone' => 'nullable|string',
        'type' => 'nullable|string',
        'country' => 'nullable|string',
        'address' => 'nullable|string',
        'description' => 'nullable|string',
        'founded_at' => 'nullable|date',
        'linkedin' => 'nullable|url',
        'website' => 'nullable|url',
        'facebook' => 'nullable|url',
        'instagram' => 'nullable|url',
        'specialization' => 'nullable|string',
        'team_size' => 'nullable|string',
        'founded' => 'nullable|date',
        'about' => 'nullable|string',
    ]);

    $company = Company::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'type' => $request->type,
        'country' => $request->country,
        'address' => $request->address,
        'description' => $request->description,
        'founded_at' => $request->founded_at,
        'linkedin' => $request->linkedin,
        'website' => $request->website,
        'facebook' => $request->facebook,
        'instagram' => $request->instagram,
        'specialization' => $request->specialization,
        'team_size' => $request->team_size,
        'founded' => $request->founded,
        'about' => $request->about,
    ]);

    $token = $company->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Company registered successfully',
        'company' => $company,
        'token' => $token,
    ], 201);
}

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $company = Company::where('email', $request->email)->first();

    //     if (!$company || !Hash::check($request->password, $company->password)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     $token = $company->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login successful',
    //         'token' => $token,
    //         'token_type' => 'Bearer',
    //     ]);
    // }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
