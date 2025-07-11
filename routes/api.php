<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\CompanyAuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\API\JobPostController;
use App\Http\Controllers\Api\SkillsController;
use App\Http\Controllers\Api\studentProfileController;
use App\Http\Controllers\api\auth\StudentAuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\API\JobApplicationController;

Route::prefix('student-profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [studentProfileController::class, 'index']);
    // Route::post('/education', [studentProfileController::class, 'storeEducation']);
    // Route::get('/education', [studentProfileController::class, 'getEducation']);

    Route::post('/skills', [SkillsController::class, 'storeskills']);
    Route::get('/skills', [SkillsController::class, 'index']);
    Route::delete('/skill/{id}', [SkillsController::class, 'destroy']);
});






    
Route::prefix('company')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::put('/', [CompanyController::class, 'update']);
    Route::get('/jobs', [CompanyController::class, 'company_jobs']);
    Route::post('/jobs', [CompanyController::class, 'create_job']);
    Route::get('/jobs/{jobId}/applications', [CompanyController::class, 'getJobApplications']);
    Route::put('/jobs/{jobId}', [CompanyController::class, 'update_job']);

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





// Job Post Routes
Route::get('/jobs', [JobPostController::class, 'index']);
Route::get('/jobs/{id}', [JobPostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs', [JobPostController::class, 'store']);
});




// Job Application Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs/{id}/apply', [JobApplicationController::class, 'apply']);
});



Route::post('/job-applications/{id}/status', [JobApplicationController::class, 'updateStatus']);
