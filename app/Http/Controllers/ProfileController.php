<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
   
    public function userProfile(Request $request)
    {
        $userId =  $request->user();

        $user = User::with('profile')->findOrFail($userId->id);

        if (!$user) {
            return response()->json(['massage' => 'User not found!']);
        }

        return response()->json(['data' => $user]);
    }
    public function update(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'bio' => 'nullable|string',
        ]);

        $userId =  $request->user();

        // Check if the profile exists
        $profile = Profile::where('user_id', $userId->id)->first();

        if ($profile) {

            $profile->update([
                'user_id' => $userId->id,
                'first_name' => $request->first_name ?? '',
                'last_name' => $request->last_name ?? '',
                'phone' => $request->phone ?? '',
                'profile_picture' => '',
                'bio' => $request->bio ?? '',
            ]);
            return response()->json(['message' => 'Profile update successfully', 'data' => $profile]);
        } else {

            $profile = Profile::create([
                'user_id' => $userId->id,
                'first_name' => $request->first_name ?? '',
                'last_name' => $request->last_name ?? '',
                'phone' => $request->phone ?? '',
                'profile_picture' => '',
                'bio' => $request->bio ?? '',
            ]);
            return response()->json(['message' => 'Profile update successfully', 'data' => $profile]);
        }
    }
}
