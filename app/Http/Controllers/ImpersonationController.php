<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function impersonate(Request $request, User $user)
    {
        $admin = $request->user();

        // Ensure current user is Super Admin or has impersonation rights
        if (! $admin->isSuperAdmin() && ! $admin->hasRole('admin')) {
            abort(403, 'Unauthorized impersonation attempt.');
        }

        if ($admin->id === $user->id) {
            return redirect()->back()->with('error', 'You are already logged in as yourself.');
        }

        // Store admin ID in session
        session(['impersonator_id' => $admin->id]);

        // Login as target user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}.");
    }

    public function stop(Request $request)
    {
        if (! session()->has('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $adminId = session()->pull('impersonator_id');
        $admin = User::findOrFail($adminId);

        // Re-login as original admin
        Auth::login($admin);

        return redirect()->route('security.roles')->with('success', 'Returned to original administrator account.');
    }
}
