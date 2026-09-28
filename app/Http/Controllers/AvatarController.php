<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'avatar_type' => 'required|in:initials,gravatar,custom',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = $request->user();
        $user->avatar_type = $request->avatar_type;

        if ($request->avatar_type === 'custom' && $request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile avatar preference updated successfully.');
    }
}
