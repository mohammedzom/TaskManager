<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:32|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'invaild email or password',
            ], 401);
        } else {
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_Token')->plainTextToken;

            return response()->json([
                'message' => 'Login Successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        // for delete all tokens
        // Auth::user()->tokens->each(function ($token, $key) {
        //     $token->delete();
        // });

        return response()->json('Successfully logged out');
    }

    public function getProfile($id)
    {
        $profile = User::findOrFail($id)->profile;

        return response()->json($profile, 200);
    }

    public function getUserTasks($id)
    {
        $tasks = User::findOrFail($id)->tasks;

        return response()->json($tasks, 200);
    }

    public function GetUser()
    {
        $user_id = Auth::user()->id;
        $UserData = User::with('profile')->findOrFail($user_id);

        return new UserResource($UserData);
    }

    public function GetUsers()
    {
        $UserData = User::with('profile')->get();

        return UserResource::collection($UserData);
    }
}
