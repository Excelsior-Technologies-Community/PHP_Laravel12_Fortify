<!DOCTYPE html>
<html>
<head>
    <title>Two-Factor Authentication</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .card {
            background: white;
            width: 400px;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 12px;
        }

        .description {
            text-align: center;
            color: #666;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 7px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 7px;
            background: #11998e;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #0f7f77;
        }

        .error {
            color: #dc3545;
            margin-bottom: 15px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            color: #999;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Two-Factor Authentication</h2>

    <p class="description">
        Enter the 6-digit authentication code from your
        authenticator application.
    </p>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/two-factor-challenge">
        @csrf

        <input
            type="text"
            name="code"
            placeholder="6-digit authentication code"
            inputmode="numeric"
            autocomplete="one-time-code"
            maxlength="6"
        >

        <button type="submit">
            Verify Code
        </button>
    </form>

    <div class="divider">
        OR
    </div>

    <form method="POST" action="/two-factor-challenge">
        @csrf

        <input
            type="text"
            name="recovery_code"
            placeholder="Recovery Code"
            autocomplete="off"
        >

        <button type="submit">
            Use Recovery Code
        </button>
    </form>

</div>

</body>
</html>