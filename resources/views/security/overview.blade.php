<!DOCTYPE html>
<html>

<head>

    <title>Security Overview</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f9;
        }

        .navbar {
            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1100px;
            margin: 35px auto;
            padding: 20px;
        }

        .stats {
            display: grid;
            grid-template-columns:
                repeat(4, 1fr);
            gap: 20px;
            margin: 25px 0;
        }

        .stat {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 6px 20px rgba(0,0,0,.08);
        }

        .stat h2 {
            font-size: 32px;
            margin: 0 0 8px;
        }

        .stat p {
            color: #777;
            margin: 0;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow:
                0 6px 20px rgba(0,0,0,.08);
        }

        .warning {
            background: #fff3cd;
            color: #664d03;
            padding: 15px;
            border-radius: 8px;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 8px;
        }

        .links a {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        @media(max-width:800px) {

            .stats {
                grid-template-columns: 1fr 1fr;
            }

        }

    </style>

</head>

<body>

<div class="navbar">

    <strong>Security Overview</strong>

    <a href="{{ route('dashboard') }}">
        Dashboard
    </a>

</div>

<div class="container">

    <h1>Security Overview</h1>

    <p>
        Monitor your account security and authentication activity.
    </p>

    <div class="stats">

        <div class="stat">

            <h2>
                {{ $totalActivities }}
            </h2>

            <p>
                Total Activities
            </p>

        </div>

        <div class="stat">

            <h2>
                {{ $successfulLogins }}
            </h2>

            <p>
                Successful Logins
            </p>

        </div>

        <div class="stat">

            <h2>
                {{ $failedAttempts }}
            </h2>

            <p>
                Failed Attempts
            </p>

        </div>

        <div class="stat">

            <h2>
                {{ $activeSessions }}
            </h2>

            <p>
                Active Sessions
            </p>

        </div>

    </div>

    <div class="card">

        <h2>Last Successful Login</h2>

        @if($lastLogin)

            <p>
                <strong>Date:</strong>
                {{ $lastLogin->created_at->format('d M Y, h:i A') }}
            </p>

            <p>
                <strong>IP:</strong>
                {{ $lastLogin->ip_address ?? 'Unknown' }}
            </p>

        @else

            <p>
                No successful login activity found.
            </p>

        @endif

    </div>

    <div class="card">

        <h2>Failed Attempts — Last 7 Days</h2>

        @if($recentFailedAttempts > 0)

            <div class="warning">

                {{ $recentFailedAttempts }}
                failed authentication attempt(s)
                were recorded during the last 7 days.

            </div>

        @else

            <div class="success">

                No failed authentication attempts
                were recorded during the last 7 days.

            </div>

        @endif

    </div>

    <div class="card links">

        <h2>Security Tools</h2>

        <a href="{{ route('security.profile') }}">
            👤 Profile
        </a>

        <a href="{{ route('security.password') }}">
            🔐 Change Password
        </a>

        <a href="{{ route('security.login-history') }}">
            📋 Login History
        </a>

        <a href="{{ route('security.sessions') }}">
            💻 Sessions
        </a>

        <a href="{{ route('security.two-factor') }}">
            🛡️ 2FA
        </a>

        <a href="{{ route('security.delete-account') }}">
            🗑️ Delete Account
        </a>

    </div>

</div>

</body>
</html>