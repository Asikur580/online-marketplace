<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function userProfile($id)
    {
        $user = User::with('profile')->findOrFail($id);

        if(!$user){
            return response()->json(['massage' => 'User not found!']);
        }
        
        return response()->json(['data' => $user]);
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'profile_picture' => 'nullable|max:255',
            'bio' => 'nullable|string',
        ]);

        // Check if the profile exists
        $profile = Profile::where('user_id', $validated['user_id'])->first();

        // If the profile exists, update it; otherwise, create a new one
        if ($profile) {         

            $profile->update($validated);
            return response()->json(['message' => 'Profile update successfully','data' =>$profile]);

        } else {           

            $profile = Profile::create($validated);
            return response()->json(['message' => 'Profile update successfully','data' =>$profile]);

        }
    }
}
