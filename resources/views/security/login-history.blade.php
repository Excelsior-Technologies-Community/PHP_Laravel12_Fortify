<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login History - Security</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #212529;
        }

        .container {
            width: 95%;
            max-width: 1400px;
            margin: 40px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
        }

        .header p {
            margin: 8px 0 0;
            color: #6c757d;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #0d6efd;
            color: #fff;
        }

        .btn-success {
            background: #198754;
            color: #fff;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn:hover {
            opacity: .9;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .stat-title {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }

        .filter-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
            margin-bottom: 25px;
        }

        .filter-card h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto auto;
            gap: 12px;
            align-items: end;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
        }

        .table-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .table-header h3 {
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th,
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-danger {
            background: #f8d7da;
            color: #842029;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #41464b;
        }

        .user-agent {
            max-width: 350px;
            word-break: break-word;
            color: #495057;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric Pagination
        |--------------------------------------------------------------------------
        */

        .pagination-wrapper {
            padding: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .numeric-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .numeric-pagination a,
        .numeric-pagination span {
            min-width: 38px;
            height: 38px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            background: #fff;
            color: #0d6efd;
        }

        .numeric-pagination a:hover {
            background: #e9f2ff;
        }

        .numeric-pagination .active {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .numeric-pagination .dots {
            border: none;
            background: transparent;
            color: #6c757d;
            min-width: 25px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
        }

        @media (max-width: 1000px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {

            .container {
                width: 92%;
                margin: 25px auto;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .numeric-pagination {
                gap: 5px;
            }

            .numeric-pagination a,
            .numeric-pagination span {
                min-width: 34px;
                height: 34px;
                padding: 0 8px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- Header --}}
    <div class="header">

        <div>
            <h1>🔐 Login History</h1>

            <p>
                Review your recent login, failed authentication, and logout activity.
            </p>
        </div>

        <div class="actions">

            <a
                href="{{ route('security.overview') }}"
                class="btn btn-primary"
            >
                Security Overview
            </a>

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-secondary"
            >
                Dashboard
            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    {{-- Statistics --}}
    <div class="stats">

        <div class="stat-card">

            <div class="stat-title">
                Total Activities
            </div>

            <div class="stat-value">
                {{ $totalActivities }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Successful Logins
            </div>

            <div class="stat-value">
                {{ $successfulLogins }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Failed Attempts
            </div>

            <div class="stat-value">
                {{ $failedAttempts }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Logout Activities
            </div>

            <div class="stat-value">
                {{ $logoutCount }}
            </div>

        </div>

    </div>


    {{-- Search & Filters --}}
    <div class="filter-card">

        <h3>
            🔎 Search & Filter Login History
        </h3>

        <form
            method="GET"
            action="{{ route('security.login-history') }}"
            class="filter-form"
        >

            {{-- Search --}}
            <div class="form-group">

                <label for="search">
                    Search
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Email, IP address or browser..."
                >

            </div>


            {{-- Activity --}}
            <div class="form-group">

                <label for="activity">
                    Activity
                </label>

                <select
                    name="activity"
                    id="activity"
                    class="form-control"
                >

                    <option value="">
                        All Activities
                    </option>

                    <option
                        value="login"
                        {{ request('activity') === 'login' ? 'selected' : '' }}
                    >
                        Login
                    </option>

                    <option
                        value="failed"
                        {{ request('activity') === 'failed' ? 'selected' : '' }}
                    >
                        Failed Login
                    </option>

                    <option
                        value="logout"
                        {{ request('activity') === 'logout' ? 'selected' : '' }}
                    >
                        Logout
                    </option>

                </select>

            </div>


            {{-- Date Range --}}
            <div class="form-group">

                <label for="date_range">
                    Date Range
                </label>

                <select
                    name="date_range"
                    id="date_range"
                    class="form-control"
                >

                    <option value="">
                        All Dates
                    </option>

                    <option
                        value="today"
                        {{ request('date_range') === 'today' ? 'selected' : '' }}
                    >
                        Today
                    </option>

                    <option
                        value="7days"
                        {{ request('date_range') === '7days' ? 'selected' : '' }}
                    >
                        Last 7 Days
                    </option>

                    <option
                        value="30days"
                        {{ request('date_range') === '30days' ? 'selected' : '' }}
                    >
                        Last 30 Days
                    </option>

                </select>

            </div>


            {{-- Search Button --}}
            <div class="form-group">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    🔎 Search
                </button>

            </div>


            {{-- Reset --}}
            <div class="form-group">

                <a
                    href="{{ route('security.login-history') }}"
                    class="btn btn-secondary"
                >
                    Reset
                </a>

            </div>

        </form>


        {{-- CSV Export --}}
        <div style="margin-top: 15px;">

            <a
                href="{{ route('security.login-history.export', request()->query()) }}"
                class="btn btn-success"
            >
                📥 Export CSV
            </a>

        </div>

    </div>


    {{-- Authentication Activity --}}
    <div class="table-card">

        <div class="table-header">

            <h3>
                📋 Authentication Activity
            </h3>

        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>
                            Activity
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            IP Address
                        </th>

                        <th>
                            Browser / Device
                        </th>

                        <th>
                            Date & Time
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($activities as $activity)

                    <tr>

                        <td>
                            #{{ $activity->id }}
                        </td>


                        <td>

                            @if($activity->activity === 'login')

                                <span class="badge badge-success">
                                    Login
                                </span>

                            @elseif($activity->activity === 'failed')

                                <span class="badge badge-danger">
                                    Failed Login
                                </span>

                            @elseif($activity->activity === 'logout')

                                <span class="badge badge-secondary">
                                    Logout
                                </span>

                            @else

                                <span class="badge badge-secondary">
                                    {{ ucfirst($activity->activity) }}
                                </span>

                            @endif

                        </td>


                        <td>
                            {{ $activity->email ?? '-' }}
                        </td>


                        <td>
                            {{ $activity->ip_address ?? '-' }}
                        </td>


                        <td class="user-agent">
                            {{ $activity->user_agent ?? '-' }}
                        </td>


                        <td>

                            {{ $activity->created_at
                                ? $activity->created_at->format('d M Y, h:i A')
                                : '-'
                            }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="empty"
                        >
                            No authentication activity found.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- Numeric Only Pagination --}}
        @if($activities->hasPages())

            <div class="pagination-wrapper">

                <div class="numeric-pagination">

                    @php
                        $currentPage = $activities->currentPage();
                        $lastPage = $activities->lastPage();
                        $queryParams = request()->query();
                    @endphp


                    {{-- First page --}}
                    @if($currentPage > 3)

                        <a
                            href="{{ $activities->url(1) }}"
                        >
                            1
                        </a>

                        @if($currentPage > 4)

                            <span class="dots">
                                ...
                            </span>

                        @endif

                    @endif


                    {{-- Nearby page numbers --}}
                    @for(
                        $page = max(1, $currentPage - 2);
                        $page <= min($lastPage, $currentPage + 2);
                        $page++
                    )

                        @if($page == $currentPage)

                            <span class="active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $activities->url($page) }}"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Last page --}}
                    @if($currentPage < $lastPage - 2)

                        @if($currentPage < $lastPage - 3)

                            <span class="dots">
                                ...
                            </span>

                        @endif

                        <a
                            href="{{ $activities->url($lastPage) }}"
                        >
                            {{ $lastPage }}
                        </a>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

</body>
</html>