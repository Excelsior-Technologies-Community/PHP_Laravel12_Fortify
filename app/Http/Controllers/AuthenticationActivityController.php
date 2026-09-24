<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuthenticationActivityController extends Controller
{
    /**
     * Display authentication activity history.
     */
    public function index(Request $request)
    {
        $query = LoginActivity::query()
            ->where(
                'user_id',
                $request->user()->id
            );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
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

        /*
        |--------------------------------------------------------------------------
        | Activity Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('activity') &&
            in_array(
                $request->activity,
                ['login', 'failed', 'logout']
            )
        ) {
            $query->where(
                'activity',
                $request->activity
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->date_range === 'today') {
            $query->whereDate(
                'created_at',
                today()
            );
        }

        if ($request->date_range === '7days') {
            $query->where(
                'created_at',
                '>=',
                now()->subDays(7)
            );
        }

        if ($request->date_range === '30days') {
            $query->where(
                'created_at',
                '>=',
                now()->subDays(30)
            );
        }

        $activities = $query
            ->oldest('created_at')
            ->paginate(5)
            ->withQueryString();

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

        return view(
            'security.login-history',
            compact(
                'activities',
                'totalActivities',
                'successfulLogins',
                'failedAttempts',
                'logoutCount'
            )
        );
    }

    /**
     * Export authentication history as CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = LoginActivity::query()
            ->where(
                'user_id',
                $request->user()->id
            );

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
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

        if (
            $request->filled('activity') &&
            in_array(
                $request->activity,
                ['login', 'failed', 'logout']
            )
        ) {
            $query->where(
                'activity',
                $request->activity
            );
        }

        if ($request->date_range === 'today') {
            $query->whereDate(
                'created_at',
                today()
            );
        }

        if ($request->date_range === '7days') {
            $query->where(
                'created_at',
                '>=',
                now()->subDays(7)
            );
        }

        if ($request->date_range === '30days') {
            $query->where(
                'created_at',
                '>=',
                now()->subDays(30)
            );
        }

        $activities = $query
            ->latest('created_at')
            ->get();

        return response()->streamDownload(
            function () use ($activities) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                fputcsv($handle, [
                    'ID',
                    'Activity',
                    'Email',
                    'IP Address',
                    'User Agent',
                    'Date & Time',
                ]);

                foreach ($activities as $activity) {
                    fputcsv($handle, [
                        $activity->id,
                        strtoupper($activity->activity),
                        $activity->email,
                        $activity->ip_address,
                        $activity->user_agent,
                        optional(
                            $activity->created_at
                        )->format(
                            'Y-m-d H:i:s'
                        ),
                    ]);
                }

                fclose($handle);
            },
            'authentication-activity.csv',
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}