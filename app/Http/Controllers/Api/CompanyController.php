<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\JobPostResource;
use App\Http\Resources\JobApplicationResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Log;



class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::where('id',Auth::id())->first();
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return new CompanyResource($company);
    }
    public function get_company_by_id($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request)
    {
        Log::info('Data received:', $request->all()); 

        $company = Company::find(Auth::id());
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $data = $request->validated();
        Log::info('Validated data:', $data);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('company','public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('company','public');
        }

        Log::info('Data before save:', $data);
        $company->fill($data);
        $company->save();
        Log::info('Company after save:', $company->toArray());

        $response = [
            'message' => 'Company updated successfully',
            'company' => new CompanyResource($company),
            'debug' => [
                'received_data' => $request->all(),
                'validated_data' => $data,
                'saved_data' => $company->toArray()
            ]
        ];
        
        return response()->json($response);
    }
}
