<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Display password page.
     */
    public function index(Request $request)
    {
        return view('security.password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update password.
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make(
                $request->password
            ),
        ]);

        return back()->with(
            'success',
            'Password changed successfully.'
        );
    }
}