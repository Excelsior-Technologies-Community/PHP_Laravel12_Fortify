<!DOCTYPE html>
<html>

<head>

    <title>Fortify Security Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );
        }

        .navbar {
            background: rgba(255,255,255,.15);
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
            max-width: 1150px;
            margin: 45px auto;
            padding: 20px;
        }

        .welcome-card {
            background: white;
            padding: 35px;
            text-align: center;
            border-radius: 14px;
            box-shadow:
                0 20px 40px rgba(0,0,0,.2);
            margin-bottom: 30px;
        }

        .avatar {
            width: 70px;
            height: 70px;
            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            font-weight: bold;
            margin: 0 auto 20px;
        }

        .security-grid {
            display: grid;
            grid-template-columns:
                repeat(3, 1fr);
            gap: 20px;
        }

        .security-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 10px 25px rgba(0,0,0,.15);
        }

        .security-card h2 {
            font-size: 19px;
        }

        .security-card p {
            color: #666;
            line-height: 1.5;
            min-height: 65px;
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

        .danger a {
            background: #dc3545;
        }

        @media(max-width:800px) {

            .security-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="navbar">

    <h3>
        Fortify Security Dashboard
    </h3>

    <form
        method="POST"
        action="/logout"
    >

        @csrf

        <button class="logout-btn">
            Logout
        </button>

    </form>

</div>

<div class="container">

    <div class="welcome-card">

        <div class="avatar">

            {{ strtoupper(
                substr(
                    auth()->user()->name,
                    0,
                    1
                )
            ) }}

        </div>

        <h1>
            Welcome,
            {{ auth()->user()->name }}
        </h1>

        <p>
            {{ auth()->user()->email }}
        </p>

        <strong>
            Fortify Authentication & Security
        </strong>

    </div>

    <div class="security-grid">

        <div class="security-card">

            <h2>
                📊 Security Overview
            </h2>

            <p>
                View your complete authentication
                security statistics and recent activity.
            </p>

            <a href="{{ route('security.overview') }}">
                Open Overview
            </a>

        </div>

        <div class="security-card">

            <h2>
                👤 Profile Management
            </h2>

            <p>
                Update your name and email address.
            </p>

            <a href="{{ route('security.profile') }}">
                Manage Profile
            </a>

        </div>

        <div class="security-card">

            <h2>
                🔐 Change Password
            </h2>

            <p>
                Change your password with current-password
                verification and strength checking.
            </p>

            <a href="{{ route('security.password') }}">
                Change Password
            </a>

        </div>

        <div class="security-card">

            <h2>
                🛡️ Two-Factor Authentication
            </h2>

            <p>
                Manage 2FA, QR setup and recovery codes.
            </p>

            <a href="{{ route('security.two-factor') }}">
                Manage 2FA
            </a>

        </div>

        <div class="security-card">

            <h2>
                📋 Login History
            </h2>

            <p>
                Search, filter and export login,
                failed and logout activity.
            </p>

            <a href="{{ route('security.login-history') }}">
                View History
            </a>

        </div>

        <div class="security-card">

            <h2>
                💻 Active Sessions
            </h2>

            <p>
                Search active devices and revoke
                unwanted sessions.
            </p>

            <a href="{{ route('security.sessions') }}">
                Manage Sessions
            </a>

        </div>

        <div class="security-card danger">

            <h2>
                🗑️ Delete Account
            </h2>

            <p>
                Permanently delete your account after
                confirming your password.
            </p>

            <a href="{{ route('security.delete-account') }}">
                Delete Account
            </a>

        </div>

    </div>

</div>

</body>

</html>