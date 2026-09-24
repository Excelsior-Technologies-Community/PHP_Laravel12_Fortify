<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display account deletion page.
     */
    public function index(Request $request)
    {
        return view('security.delete-account', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Delete account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'current_password',
            ],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with(
            'status',
            'Your account has been deleted successfully.'
        );
    }
}