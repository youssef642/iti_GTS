<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\CompanyAuthController;
use App\Http\Controllers\api\auth\StudentAuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\API\JobPostController;
use App\Http\Controllers\Api\SkillsController;
use App\Http\Controllers\Api\studentProfileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('student-profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [studentProfileController::class, 'index']);
    Route::post('/experience', [studentProfileController::class, 'storeexperience']);
    Route::post('/education', [studentProfileController::class, 'storeEducation']);
    Route::get('/education', [studentProfileController::class, 'getEducation']);

    Route::post('/skills', [SkillsController::class, 'storeskills']);
    Route::get('/skills', [SkillsController::class, 'skills']);
    Route::delete('/skills', [SkillsController::class, 'deleteSkill']);
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\CompanyAuthController;


// Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);


    
});




Route::prefix('company')->group(function () {
    Route::post('/register', [CompanyAuthController::class, 'register']);
    Route::post('/login', [CompanyAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [CompanyAuthController::class, 'logout']);
      
    });
});







Route::prefix('student')->group(function () {
    Route::post('/register', [StudentAuthController::class, 'register']);
    Route::post('/login', [StudentAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [StudentAuthController::class, 'logout']);
       
    });
});    






Route::get('/jobs', [JobPostController::class, 'index']);
Route::get('/jobs/{id}', [JobPostController::class, 'show']);

// فقط شركة مسجلة دخول تقدر تنشر وظيفة
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs', [JobPostController::class, 'store']);
});




use App\Http\Controllers\API\JobApplicationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs/{id}/apply', [JobApplicationController::class, 'apply']);
});