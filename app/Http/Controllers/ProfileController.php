<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;

        return new ProfileResource($profile, 200);

    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }
        $profile = Auth::user()->profile()->create($validated);

        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => new ProfileResource($profile),
        ], 201);
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user()->profile;
        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($profile->image) {
                if (Storage::disk('public')->exists($profile->image)) {
                    Storage::disk('public')->delete($profile->image);
                }
            }
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }
        $profile->update($validated);

        return new ProfileResource($profile);

    }

    public function destroy()
    {
        $profile = Auth::user()->profile;
        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        if ($profile->image) {
            if (Storage::disk('public')->exists($profile->image)) {
                Storage::disk('public')->delete($profile->image);
            }
        }
        $profile->delete();

        return response()->json(null, 204);
    }
}
