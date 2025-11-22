<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class);
Route::apiResource('profile', ProfileController::class);

Route::get('user/{id}/profile', [UserController::class, 'getProfile']);
Route::get('user/{id}/tasks', [UserController::class, 'getUserTasks']);
Route::get('task/{id}/user', [TaskController::class, 'getTaskUser']);
