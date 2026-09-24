<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Active Sessions - Security</title>

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
            max-width: 1300px;
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

        .btn-danger {
            background: #dc3545;
            color: #fff;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn:hover {
            opacity: .9;
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
            grid-template-columns: 1fr auto auto;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
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
            min-width: 850px;
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
            padding: 45px;
            color: #6c757d;
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

        .current-session {
            font-weight: 700;
        }

        .revoke-form {
            display: inline;
        }

        @media (max-width: 800px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>💻 Active Sessions</h1>

            <p>
                View devices and browsers currently signed in to your account.
            </p>
        </div>

        <div class="actions">

            <a href="{{ route('security.overview') }}"
               class="btn btn-primary">
                Security Overview
            </a>

            <a href="{{ route('dashboard') }}"
               class="btn btn-secondary">
                Dashboard
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Session Search --}}
    <div class="filter-card">

        <h3>🔎 Search Active Sessions</h3>

        <form method="GET"
              action="{{ route('security.sessions') }}"
              class="filter-form">

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
                    placeholder="Search IP address or device/browser..."
                >

            </div>

            <div class="form-group">

                <button type="submit"
                        class="btn btn-primary">
                    🔎 Search
                </button>

            </div>

            <div class="form-group">

                <a href="{{ route('security.sessions') }}"
                   class="btn btn-secondary">
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- Sessions --}}
    <div class="table-card">

        <div class="table-header">

            <h3>
                🖥️ Current Sessions
            </h3>

            <form
                method="POST"
                action="{{ route('security.sessions.revoke-others') }}"
                onsubmit="return confirm('Are you sure you want to revoke all other sessions?');"
            >

                @csrf

                <button type="submit"
                        class="btn btn-danger">
                    🗑️ Revoke Other Sessions
                </button>

            </form>

        </div>

        <div class="table-responsive">

            <table>

                <thead>

                    <tr>
                        <th>Session ID</th>
                        <th>IP Address</th>
                        <th>Device / Browser</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($sessions as $session)

                    <tr>

                        <td>
                            {{ \Illuminate\Support\Str::limit($session->id, 18) }}
                        </td>

                        <td>
                            {{ $session->ip_address ?? '-' }}
                        </td>

                        <td class="user-agent">
                            {{ $session->user_agent ?? '-' }}
                        </td>

                        <td>
                            {{ $session->last_activity
                                ? date('d M Y, h:i A', $session->last_activity)
                                : '-' }}
                        </td>

                        <td>

                            @if($session->is_current)

                                <span class="badge badge-success">
                                    Current Session
                                </span>

                            @else

                                <span class="badge badge-secondary">
                                    Other Session
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($session->is_current)

                                <span class="current-session">
                                    This Device
                                </span>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('security.sessions.revoke', $session->id) }}"
                                    class="revoke-form"
                                    onsubmit="return confirm('Are you sure you want to revoke this session?');"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Revoke
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="empty">

                            No active sessions found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>