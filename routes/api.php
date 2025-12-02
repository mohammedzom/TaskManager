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

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index']);
            Route::post('/', [ProfileController::class, 'store']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::delete('/', [ProfileController::class, 'destroy']);
        });

        Route::get('user/{id}/profile', [UserController::class, 'getProfile']);
        Route::get('user/{id}/tasks', [UserController::class, 'getUserTasks']);

        Route::prefix('tasks')->group(function () {

            Route::get('/ordered', [TaskController::class, 'getTaskOrderByPriority']);
            Route::get('/ordered/{sort_direction}', [TaskController::class, 'getTaskOrderByPriorityByUser']);

            Route::post('/{id}/favorite', [TaskController::class, 'addToFavorites']);
            Route::delete('/{id}/favorite', [TaskController::class, 'removeFromFavorites']);
            Route::get('/favorite', [TaskController::class, 'getFavoriteTasks']);
            Route::get('/all', [TaskController::class, 'getAllTasks'])->middleware('CheckUser');
            Route::get('/{id}/user', [TaskController::class, 'getTaskUser']);
            Route::apiResource('', TaskController::class);
            Route::post('/{taskId}/categories', [TaskController::class, 'addCategoriesToTasks']);
            Route::get('/{taskId}/categories', [TaskController::class, 'getTasksCategories']);
        });
        Route::get('categories/{categoryId}/tasks', [TaskController::class, 'getCategoriesTasks']);

    }
);
