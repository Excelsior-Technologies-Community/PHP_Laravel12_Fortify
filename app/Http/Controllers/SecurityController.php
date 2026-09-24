<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    /**
     * Security overview.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $totalActivities = LoginActivity::where(
            'user_id',
            $userId
        )->count();

        $successfulLogins = LoginActivity::where(
            'user_id',
            $userId
        )
            ->where('activity', 'login')
            ->count();

        $failedAttempts = LoginActivity::where(
            'user_id',
            $userId
        )
            ->where('activity', 'failed')
            ->count();

        $logoutCount = LoginActivity::where(
            'user_id',
            $userId
        )
            ->where('activity', 'logout')
            ->count();

        $activeSessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->count();

        $lastLogin = LoginActivity::where(
            'user_id',
            $userId
        )
            ->where('activity', 'login')
            ->latest('created_at')
            ->first();

        $recentFailedAttempts = LoginActivity::where(
            'user_id',
            $userId
        )
            ->where('activity', 'failed')
            ->where(
                'created_at',
                '>=',
                now()->subDays(7)
            )
            ->count();

        return view('security.overview', compact(
            'totalActivities',
            'successfulLogins',
            'failedAttempts',
            'logoutCount',
            'activeSessions',
            'lastLogin',
            'recentFailedAttempts'
        ));
    }
}