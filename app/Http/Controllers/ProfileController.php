<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Profile::all();

        return response()->json($profile, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request): JsonResponse
    {
        $profile = Profile::create($request->validated());

        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => $profile,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $profile = Profile::where('user_id', $id)->firstOrFail();

        return response()->json($profile, 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id)
    {
        $profile = Profile::where('user_id', $id)->firstOrFail();
        $profile->update($request->validated());

        return response()->json($profile, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        return response()->json(null, 204);
    }
}
