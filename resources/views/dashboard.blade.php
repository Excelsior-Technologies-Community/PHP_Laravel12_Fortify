<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .navbar {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .logout-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
        }

        .welcome-card {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }

        .avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            font-weight: bold;
            margin: 0 auto 20px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .welcome {
            color: #666;
            margin-bottom: 15px;
        }

        .badge {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white;
            padding: 10px 22px;
            border-radius: 25px;
            display: inline-block;
            font-size: 14px;
        }

        .security-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .security-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
        }

        .security-card h2 {
            margin-bottom: 12px;
            font-size: 20px;
        }

        .security-card p {
            color: #666;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .security-card a {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .security-card a:hover {
            background: #5563d6;
        }

        @media(max-width: 800px) {

            .security-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="navbar">

    <h3>My Dashboard</h3>

    <form method="POST" action="/logout">

        @csrf

        <button class="logout-btn">
            Logout
        </button>

    </form>

</div>

<div class="container">

    <div class="welcome-card">

        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <h1>Dashboard</h1>

        <p class="welcome">
            Welcome,
            <strong>{{ auth()->user()->name }}</strong>
        </p>

        <div class="badge">
            Logged In Successfully
        </div>

    </div>

    <div class="security-grid">

        <div class="security-card">

            <h2>🔐 Two-Factor Authentication</h2>

            <p>
                Manage 2FA, QR-code setup, authentication confirmation
                and recovery codes.
            </p>

            <a href="{{ route('security.two-factor') }}">
                Manage 2FA
            </a>

        </div>

        <div class="security-card">

            <h2>📋 Login History</h2>

            <p>
                Review successful logins, failed authentication attempts,
                IP addresses and browser information.
            </p>

            <a href="{{ route('security.login-history') }}">
                View Activity
            </a>

        </div>

        <div class="security-card">

            <h2>💻 Active Sessions</h2>

            <p>
                Review active devices and sessions and revoke sessions
                that you no longer want to keep active.
            </p>

            <a href="{{ route('security.sessions') }}">
                Manage Sessions
            </a>

        </div>

    </div>

</div>

</body>

</html>