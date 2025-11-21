<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getProfile($id)
    {
        $profile = User::find($id)->profile;

        return response()->json($profile, 200);
    }
}
