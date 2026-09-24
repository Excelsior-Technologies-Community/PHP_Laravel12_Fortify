<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    /**
     * Display active sessions.
     */
    public function index(Request $request)
    {
        $query = DB::table('sessions')
            ->where(
                'user_id',
                $request->user()->id
            );

        /*
        |--------------------------------------------------------------------------
        | Session Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'ip_address',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'user_agent',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $sessions = $query
            ->orderByDesc('last_activity')
            ->get();

        $currentSessionId = $request
            ->session()
            ->getId();

        $sessionData = $sessions->map(
            function ($session) use ($currentSessionId) {

                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => $session->last_activity,
                    'is_current' =>
                        $session->id === $currentSessionId,
                ];
            }
        );

        return view(
            'security.sessions',
            [
                'sessions' => $sessionData,
            ]
        );
    }

    /**
     * Revoke one session.
     */
    public function revoke(
        Request $request,
        string $sessionId
    ) {
        $currentSessionId = $request
            ->session()
            ->getId();

        if ($sessionId === $currentSessionId) {
            return back()->with(
                'error',
                'You cannot revoke your current session.'
            );
        }

        DB::table('sessions')
            ->where('id', $sessionId)
            ->where(
                'user_id',
                $request->user()->id
            )
            ->delete();

        return back()->with(
            'success',
            'The selected session has been revoked successfully.'
        );
    }

    /**
     * Revoke all sessions except current session.
     */
    public function revokeOthers(Request $request)
    {
        $currentSessionId = $request
            ->session()
            ->getId();

        DB::table('sessions')
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'id',
                '!=',
                $currentSessionId
            )
            ->delete();

        return back()->with(
            'success',
            'All other sessions have been revoked successfully.'
        );
    }
}