<!DOCTYPE html>
<html>

<head>

    <title>Active Sessions</title>

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
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
        }

        .session {
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .session-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .current {
            background: #d1e7dd;
            color: #0f5132;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .other {
            background: #e2e3e5;
            color: #41464b;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .details {
            color: #666;
            line-height: 1.8;
        }

        .details strong {
            color: #333;
        }

        button {
            padding: 9px 15px;
            border: none;
            border-radius: 6px;
            background: #dc3545;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #bb2d3b;
        }

        .revoke-all {
            background: #dc3545;
            padding: 12px 18px;
        }

        .message {
            padding: 14px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .error {
            background: #f8d7da;
            color: #842029;
        }

        .back {
            display: inline-block;
            margin-top: 10px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .user-agent {
            word-break: break-word;
        }

    </style>

</head>

<body>

<div class="navbar">

    <strong>Active Session & Device Management</strong>

    <a href="/dashboard">
        Dashboard
    </a>

</div>

<div class="container">

    <h1>Active Sessions</h1>

    <p>
        Review devices currently signed into your account.
    </p>

    @if (session('success'))

        <div class="message success">
            {{ session('success') }}
        </div>

    @endif

    @if (session('error'))

        <div class="message error">
            {{ session('error') }}
        </div>

    @endif

    <div class="card">

        <form
            method="POST"
            action="/security/sessions/revoke-others"
            onsubmit="return confirm('Revoke all other sessions?');"
        >

            @csrf

            <button class="revoke-all" type="submit">
                Revoke All Other Sessions
            </button>

        </form>

    </div>

    <div class="card">

        @forelse ($sessions as $session)

            <div class="session">

                <div class="session-header">

                    <strong>
                        Session
                    </strong>

                    @if ($session['is_current'])

                        <span class="current">
                            CURRENT SESSION
                        </span>

                    @else

                        <span class="other">
                            OTHER SESSION
                        </span>

                    @endif

                </div>

                <div class="details">

                    <div>
                        <strong>IP Address:</strong>
                        {{ $session['ip_address'] ?? 'Unknown' }}
                    </div>

                    <div class="user-agent">

                        <strong>Browser / Device:</strong>

                        {{ $session['user_agent'] ?? 'Unknown' }}

                    </div>

                    <div>

                        <strong>Last Activity:</strong>

                        {{ \Carbon\Carbon::createFromTimestamp(
                            $session['last_activity']
                        )->format('d M Y, h:i A') }}

                    </div>

                </div>

                @if (! $session['is_current'])

                    <form
                        method="POST"
                        action="/security/sessions/{{ urlencode($session['id']) }}/revoke"
                        style="margin-top:15px;"
                        onsubmit="return confirm('Revoke this session?');"
                    >

                        @csrf

                        <button type="submit">
                            Revoke Session
                        </button>

                    </form>

                @endif

            </div>

        @empty

            <p>
                No active sessions found.
            </p>

        @endforelse

    </div>

    <a class="back" href="/dashboard">
        ← Back to Dashboard
    </a>

</div>

</body>

</html>