<!DOCTYPE html>
<html>
<head>
    <title>Two-Factor Authentication Manager</title>

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
            max-width: 950px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 14px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        h1 {
            margin-bottom: 8px;
        }

        h2 {
            margin-top: 0;
        }

        .status {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .enabled {
            background: #d1e7dd;
            color: #0f5132;
        }

        .disabled {
            background: #f8d7da;
            color: #842029;
        }

        button {
            padding: 11px 18px;
            border: none;
            border-radius: 7px;
            background: #667eea;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #5563d6;
        }

        .danger {
            background: #dc3545;
        }

        .success {
            background: #198754;
        }

        .qr {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            display: inline-block;
            border-radius: 10px;
        }

        .code-list {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
        }

        .code {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: monospace;
        }

        .message {
            padding: 14px;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .warning {
            padding: 14px;
            background: #fff3cd;
            color: #664d03;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="navbar">

    <strong>Fortify Security Manager</strong>

    <a href="/dashboard">
        Dashboard
    </a>

</div>

<div class="container">

    <div class="card">

        <h1>Two-Factor Authentication</h1>

        <p>
            Add an additional authentication layer to protect your account.
        </p>

        @if (session('status') === 'two-factor-authentication-enabled')
            <div class="message">
                Two-factor authentication has been enabled.
                Please scan the QR code and confirm your authentication code.
            </div>
        @endif

        @if (session('status') === 'two-factor-authentication-confirmed')
            <div class="message">
                Two-factor authentication confirmed and enabled successfully.
            </div>
        @endif

        @if (session('status') === 'two-factor-authentication-disabled')
            <div class="message">
                Two-factor authentication has been disabled.
            </div>
        @endif

        @if ($user->two_factor_confirmed_at)
            <span class="status enabled">
                ENABLED
            </span>
        @else
            <span class="status disabled">
                NOT ENABLED
            </span>
        @endif

    </div>

    @if (! $user->two_factor_secret)

        <div class="card">

            <h2>Enable Two-Factor Authentication</h2>

            <p>
                Enable TOTP-based authentication using an application
                such as Google Authenticator or another compatible
                authenticator application.
            </p>

            <form method="POST" action="/user/two-factor-authentication">
                @csrf

                <button type="submit">
                    Enable 2FA
                </button>
            </form>

        </div>

    @else

        @if (! $user->two_factor_confirmed_at)

            <div class="card">

                <h2>Complete 2FA Setup</h2>

                <div class="warning">
                    Your 2FA secret has been generated.
                    Scan the QR code and confirm the generated code.
                </div>

                <div class="qr">
                    {!! $user->twoFactorQrCodeSvg() !!}
                </div>

                <h3>Confirm Authentication Code</h3>

                <form
                    method="POST"
                    action="/user/confirmed-two-factor-authentication"
                >
                    @csrf

                    <input
                        type="text"
                        name="code"
                        placeholder="Enter 6-digit code"
                        maxlength="6"
                        required
                        style="padding:11px;width:250px;border:1px solid #ccc;border-radius:6px;"
                    >

                    <button type="submit">
                        Confirm 2FA
                    </button>
                </form>

            </div>

        @else

            <div class="card">

                <h2>Recovery Codes</h2>

                <p>
                    Recovery codes can be used when you cannot access
                    your authenticator application.
                </p>

                @if ($user->recoveryCodes())
                    <div class="code-list">

                        @foreach ($user->recoveryCodes() as $code)

                            <span class="code">
                                {{ $code }}
                            </span>

                        @endforeach

                    </div>
                @endif

                <br>

                <form
                    method="POST"
                    action="/user/two-factor-recovery-codes"
                    style="margin-top:20px;"
                >
                    @csrf

                    <button type="submit">
                        Regenerate Recovery Codes
                    </button>
                </form>

            </div>

            <div class="card">

                <h2>Disable Two-Factor Authentication</h2>

                <p>
                    Disabling 2FA removes the additional authentication
                    requirement from your account.
                </p>

                <form
                    method="POST"
                    action="/user/two-factor-authentication"
                    onsubmit="return confirm('Are you sure you want to disable 2FA?');"
                >
                    @csrf
                    @method('DELETE')

                    <button class="danger" type="submit">
                        Disable 2FA
                    </button>
                </form>

            </div>

        @endif

    @endif

    <a class="back" href="/dashboard">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>