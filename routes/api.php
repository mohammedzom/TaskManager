<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(
    function () {

        Route::apiResource('profile', ProfileController::class);

        Route::get('user/{id}/profile', [UserController::class, 'getProfile']);
        Route::get('user/{id}/tasks', [UserController::class, 'getUserTasks']);

        Route::apiResource('tasks', TaskController::class);
        Route::get('task/{id}/user', [TaskController::class, 'getTaskUser']);
        Route::post('tasks/{taskId}/categories', [TaskController::class, 'addCategoriesToTasks']);
        Route::get('tasks/{taskId}/categories', [TaskController::class, 'getTasksCategories']);
        Route::get('categories/{categoryId}/tasks', [TaskController::class, 'getCategoriesTasks']);
    }
);
