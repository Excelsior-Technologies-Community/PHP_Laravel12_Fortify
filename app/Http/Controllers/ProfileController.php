<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display profile page.
     */
    public function index(Request $request)
    {
        return view('security.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }
}