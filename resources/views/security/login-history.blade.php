<!DOCTYPE html>
<html>
<head>

    <title>Authentication Activity</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            color: #333;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 1150px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
        }

        .stat h3 {
            margin: 0;
            font-size: 28px;
        }

        .stat p {
            color: #777;
            margin-bottom: 0;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8f9fa;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .login {
            background: #d1e7dd;
            color: #0f5132;
        }

        .failed {
            background: #f8d7da;
            color: #842029;
        }

        .logout {
            background: #cff4fc;
            color: #055160;
        }

        .user-agent {
            max-width: 300px;
            word-break: break-word;
            font-size: 12px;
            color: #666;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        @media(max-width: 800px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

        }

    </style>

</head>

<body>

<div class="navbar">

    <strong>Authentication Activity</strong>

    <a href="/dashboard">
        Dashboard
    </a>

</div>

<div class="container">

    <h1>Authentication Activity & Login History</h1>

    <p>
        Review recent login, failed authentication and logout activity.
    </p>

    <div class="stats">

        <div class="stat">
            <h3>{{ $totalActivities }}</h3>
            <p>Total Activities</p>
        </div>

        <div class="stat">
            <h3>{{ $successfulLogins }}</h3>
            <p>Successful Logins</p>
        </div>

        <div class="stat">
            <h3>{{ $failedAttempts }}</h3>
            <p>Failed Attempts</p>
        </div>

        <div class="stat">
            <h3>{{ $logoutCount }}</h3>
            <p>Logouts</p>
        </div>

    </div>

    <div class="card">

        <h2>Activity History</h2>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>Activity</th>
                        <th>Email</th>
                        <th>IP Address</th>
                        <th>Browser / Device</th>
                        <th>Date & Time</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($activities as $activity)

                        <tr>

                            <td>

                                @if ($activity->activity === 'login')

                                    <span class="badge login">
                                        LOGIN
                                    </span>

                                @elseif ($activity->activity === 'failed')

                                    <span class="badge failed">
                                        FAILED
                                    </span>

                                @else

                                    <span class="badge logout">
                                        LOGOUT
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $activity->email ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ $activity->ip_address ?? 'Unknown' }}
                            </td>

                            <td>
                                <div class="user-agent">
                                    {{ $activity->user_agent ?? 'Unknown' }}
                                </div>
                            </td>

                            <td>
                                {{ $activity->created_at?->format('d M Y, h:i A') }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">
                                No authentication activity found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination">

            {{ $activities->links() }}

        </div>

    </div>

    <a class="back" href="/dashboard">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>