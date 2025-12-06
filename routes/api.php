<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('/user', [UserController::class, 'GetUser']);
        Route::get('/users', [UserController::class, 'GetUsers'])->middleware('CheckUser');

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index']);
            Route::post('/', [ProfileController::class, 'store']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::delete('/', [ProfileController::class, 'destroy']);
        });

        Route::get('user/{id}/tasks', [UserController::class, 'getUserTasks'])->middleware('CheckUser');

        Route::prefix('tasks')->group(function () {
            // 1.  (Custom Routes)
            Route::get('/ordered', [TaskController::class, 'getTaskOrderByPriority']);
            Route::get('/ordered/{sort_direction}', [TaskController::class, 'getTaskOrderByPriorityByUser']);
            Route::get('/favorite', [TaskController::class, 'getFavoriteTasks']);
            Route::get('/all', [TaskController::class, 'getAllTasks'])->middleware('CheckUser');

            //
            Route::post('/{task}/favorite', [TaskController::class, 'addToFavorites']);
            Route::delete('/{task}/favorite', [TaskController::class, 'removeFromFavorites']);
            Route::get('/{task}/user', [TaskController::class, 'getTaskUser']);
            Route::post('/{task}/categories', [TaskController::class, 'addCategoriesToTasks']);
            Route::get('/{task}/categories', [TaskController::class, 'getTasksCategories']);

            Route::get('/', [TaskController::class, 'index']);   // GET /api/tasks
            Route::post('/', [TaskController::class, 'store']);  // POST /api/tasks
            Route::get('/{task}', [TaskController::class, 'show']);    // GET /api/tasks/{id}
            Route::put('/{task}', [TaskController::class, 'update']);  // PUT /api/tasks/{id}
            Route::delete('/{task}', [TaskController::class, 'destroy']); // DELETE /api/tasks/{id}
        });
        Route::get('categories/{categoryId}/tasks', [TaskController::class, 'getCategoriesTasks']);

    }
);
