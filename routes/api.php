<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TraineeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'can:isStaff'])->group(function () {
    Route::group(['prefix' => 'staff'], function () {
        Route::group(['prefix' => 'courses'], function () {
            Route::get('/', [CourseController::class, 'index']);
            Route::get('/add', [CourseController::class, 'create']);
            Route::post('/add', [CourseController::class, 'store']);
            Route::get('/edit/{id}', [CourseController::class, 'edit']);
            Route::post('/edit/{id}', [CourseController::class, 'update']);
            Route::delete('/delete/{id}', [CourseController::class, 'delete']);
            Route::get('/edit-trainees/{id}', [CourseController::class, 'editTrainee']);
            Route::post('/edit-trainees/{id}', [CourseController::class, 'updateTrainee']);
        });
        Route::group(['prefix' => 'trainees'], function () {
            Route::get('/', [TraineeController::class, 'index']);
            Route::get('/take-ten', [TraineeController::class, 'takeTen']);
            Route::get('/add', [TraineeController::class, 'create']);
            Route::post('/add', [TraineeController::class, 'store']);
            Route::get('/edit/{id}', [TraineeController::class, 'edit']);
            Route::post('/edit/{id}', [TraineeController::class, 'update']);
            Route::delete('/delete/{id}', [TraineeController::class, 'delete']);
        });
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/check-token', [AuthController::class, 'checkToken']);
});

Route::post('/login', [AuthController::class, 'login']);
