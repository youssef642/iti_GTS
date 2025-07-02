<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkillsController;
use App\Http\Controllers\Api\studentProfileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('student-profile', [StudentProfileController::class, 'index']);
    Route::post('student-profile/experience', [StudentProfileController::class, 'storeexperience']);
    Route::post('student-profile/skills', [SkillsController::class, 'storeskills']);
    Route::get('student-profile/skills', [SkillsController::class, 'skills']);
    Route::delete('student-profile/skills', [SkillsController::class, 'deleteSkill']);
    Route::post('student-profile/education', [StudentProfileController::class, 'storeEducation']);
    Route::get('student-profile/education', [StudentProfileController::class, 'getEducation']);
});