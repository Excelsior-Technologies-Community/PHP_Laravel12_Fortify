<!DOCTYPE html>
<html>
<head>
    <title>Confirm Password</title>

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
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .card {
            background: white;
            width: 380px;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .description {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
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
            background: #667eea;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #5563d6;
        }

        .error {
            color: #dc3545;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Confirm Password</h2>

    <p class="description">
        Please confirm your current password before continuing
        to the security settings.
    </p>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/user/confirm-password">
        @csrf

        <input
            type="password"
            name="password"
            placeholder="Current Password"
            required
            autofocus
        >

        <button type="submit">
            Confirm Password
        </button>
    </form>

</div>

</body>
</html>