<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;

class AuthenticationActivityController extends Controller
{
    /**
     * Display authentication activity history.
     */
    public function index(Request $request)
    {
        $activities = LoginActivity::query()
            ->where('user_id', $request->user()->id)
            ->latest('created_at')
            ->paginate(10);

        $totalActivities = LoginActivity::where(
            'user_id',
            $request->user()->id
        )->count();

        $successfulLogins = LoginActivity::where(
            'user_id',
            $request->user()->id
        )
            ->where('activity', 'login')
            ->count();

        $failedAttempts = LoginActivity::where(
            'user_id',
            $request->user()->id
        )
            ->where('activity', 'failed')
            ->count();

        $logoutCount = LoginActivity::where(
            'user_id',
            $request->user()->id
        )
            ->where('activity', 'logout')
            ->count();

        return view('security.login-history', compact(
            'activities',
            'totalActivities',
            'successfulLogins',
            'failedAttempts',
            'logoutCount'
        ));
    }
}