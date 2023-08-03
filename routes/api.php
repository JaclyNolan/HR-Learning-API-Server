<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\TrainerController;
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

        Route::group(['prefix' => 'courseCategories'], function () {
            Route::get('/', [CourseCategoryController::class, 'index']);
            Route::post('/add', [CourseCategoryController::class, 'store']);
            Route::get('/edit/{id}', [CourseCategoryController::class, 'edit']);
            Route::post('/edit/{id}', [CourseCategoryController::class, 'update']);
            Route::delete('/delete/{id}', [CourseCategoryController::class, 'delete']);
        });

        Route::group(['prefix' => 'topics'], function () {
            Route::get('/', [TopicController::class, 'index']);
            Route::get('/add', [TopicController::class, 'create']);
            Route::post('/add', [TopicController::class, 'store']);
            Route::get('/edit/{id}', [TopicController::class, 'edit']);
            Route::post('/edit/{id}', [TopicController::class, 'update']);
            Route::delete('/delete/{id}', [TopicController::class, 'delete']);
        });

        Route::group(['prefix' => 'trainers'], function () {
            Route::get('/', [TrainerController::class, 'index']);
            Route::post('/add', [TrainerController::class, 'store']);
            Route::get('/edit/{id}', [TrainerController::class, 'edit']);
            Route::post('/edit/{id}', [TrainerController::class, 'update']);
            Route::delete('/delete/{id}', [TrainerController::class, 'delete']);
        });
    });
});

Route::middleware(['auth:sanctum', 'can:isTrainer'])->group(function () {
    Route::group(['prefix' => 'trainer'], function () {
        Route::group(['prefix' => 'courses'], function () {
            Route::get('/', [CourseController::class, 'indexForTrainer']);
        });
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [TrainerController::class, 'profileForTrainer']);
            Route::post('/', [TrainerController::class, 'updateProfileForTrainer']);
        });
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/check-token', [AuthController::class, 'checkToken']);
});

Route::post('/login', [AuthController::class, 'login']);
