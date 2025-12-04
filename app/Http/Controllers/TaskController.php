<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaskController extends Controller
{
    public function getAllTasks()
    {
        $tasks = Task::all();

        return TaskResource::collection($tasks, 200);

    }

    public function index()
    {
        $tasks = Auth::user()->tasks;

        return TaskResource::collection($tasks, 200);
    }

    public function getTaskOrderByPriority()
    {
        $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "high", "medium", "low")')->get();

        return TaskResource::collection($tasks, 200);
    }

    public function getTaskOrderByPriorityByUser($sort_direction)
    {
        if (strtoupper($sort_direction) === 'ASC') {
            $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "low", "medium", "high")')->get();
        } elseif (strtoupper($sort_direction) === 'DESC') {
            $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "high", "medium", "low")')->get();
        } else {
            return response()->json(['message' => 'Invalid Sort Direction'], 422);
        }

        return new TaskResource($tasks, 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {

        $task = Auth::user()->tasks()->findOrFail($id);
        $task->update($request->validated());

        return new TaskResource($task, 200);
    }

    public function show($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);

        return new TaskResource($task, 200);
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id)->delete();

        return response()->json(null, 204);
    }

    public function getTaskUser($id)
    {
        $user = Auth::user()->tasks()->findOrFail($id)->user;

        return new UserResource($user);
    }

    public function addCategoriesToTasks(Request $request, $taskId)
    {
        $task = Auth::user()->tasks()->findOrFail($taskId);

        $task->categories()->attach($request->category_id);

        return response()->json('Category attached successfully', 200);
    }

    public function getCategoriesTasks($category_id)
    {
        $tasks = Category::findOrFail($category_id)->tasks()->where('user_id', Auth::id())->get();

        return new TaskResource($tasks);

    }

    public function getTasksCategories($taskId)
    {
        $categories = Auth::user()->tasks()->findOrFail($taskId)->categories;

        return response()->json($categories, 200);

    }

    public function addToFavorites($taskId)
    {
        if (Task::findOrFail($taskId)->user_id != Auth::user()->id) {
            throw new AccessDeniedHttpException;
        }
        Auth::user()->favoriteTasks()->syncWithoutDetaching($taskId);

        return response()->json(['message' => 'Task added to favorites'], 200);
    }

    public function removeFromFavorites($taskId)
    {
        if (Task::findOrFail($taskId)->user_id != Auth::user()->id) {
            throw new AccessDeniedHttpException;
        }
        Auth::user()->favoriteTasks()->detach($taskId);

        return response()->json(['message' => 'Task removed from favorites'], 200);
    }

    public function getFavoriteTasks()
    {
        $favorites = Auth::user()->favoriteTasks;

        return response()->json($favorites, 200);
    }
}
