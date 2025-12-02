<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Auth::user()->profile;

        return response()->json($profile, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request): JsonResponse
    {
        $user_id = Auth::user()->id;
        $existingProfile = Profile::where('user_id', $user_id)->first();
        if ($existingProfile) {
            return response()->json([
                'message' => 'User already has a profile. Use update instead.',
            ], 409); // 409 Conflict
        }
        $profile = Auth::user()->profile()->create($request->validated());

        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => $profile,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user()->profile;
        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $profile->update($request->validated());

        return response()->json($profile, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $profile = Auth::user()->profile;
        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $profile->delete();

        return response()->json(null, 204);
    }
}
