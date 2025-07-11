<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\CompanyAuthController;
use App\Http\Controllers\API\JobPostController;
use App\Http\Controllers\Api\SkillsController;
use App\Http\Controllers\Api\studentProfileController;
use App\Http\Controllers\api\auth\StudentAuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\API\JobApplicationController;
use App\Http\Controllers\ExperienceController;

Route::prefix('student-profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [studentProfileController::class, 'index']);
    Route::put('/', [studentProfileController::class, 'update']);
    Route::get('/experience', [ExperienceController::class, 'getExperience']);
    Route::post('/experience', [ExperienceController::class, 'storeExperience']);
    Route::put('/experience/{id}', [ExperienceController::class, 'updateExperience']);
    Route::delete('/experience/{id}', [ExperienceController::class, 'destroyExperience']);
    Route::post('/skills', [SkillsController::class, 'storeskills']);
    Route::get('/skills', [SkillsController::class, 'index']);
    Route::delete('/skill/{id}', [SkillsController::class, 'destroy']);
    Route::post('/jobs/{id}/apply', [JobApplicationController::class, 'studentApply']);
    Route::get('/jobs/applied', [JobApplicationController::class, 'getApplications']);
    Route::get('/jobs', [JobPostController::class, 'student_index']);
    Route::get('/jobs/{id}', [JobPostController::class, 'show']);

});


Route::prefix('company')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::put('/', [CompanyController::class, 'update']);
    Route::get('/jobs', [JobPostController::class, 'company_jobs']);
    Route::get('/jobs/{jobId}/applications', [JobApplicationController::class, 'company_job_applications']);
    Route::put('/jobs/{jobId}', [JobPostController::class, 'update_job']);
    Route::post('/jobs', [JobPostController::class, 'storejob']);
    Route::delete('/jobs/{id}', [JobPostController::class, 'destroy']);

});

// Authentication company Routes
Route::prefix('company')->group(function () {
    Route::post('/register', [CompanyAuthController::class, 'register']);
    Route::post('/login', [CompanyAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [CompanyAuthController::class, 'logout']);
      
    });
});

// Authentication student Routes

Route::prefix('student')->group(function () {
    Route::post('/register', [StudentAuthController::class, 'register']);
    Route::post('/login', [StudentAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [StudentAuthController::class, 'logout']);
       
    });
});    





