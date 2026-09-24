<!DOCTYPE html>
<html>

<head>

    <title>Delete Account</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background:
                linear-gradient(
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
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow:
                0 8px 25px rgba(0,0,0,.08);
        }

        .danger {
            background: #f8d7da;
            color: #842029;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 7px;
            box-sizing: border-box;
        }

        button {
            background: #dc3545;
            color: white;
            border: 0;
            padding: 12px 20px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="navbar">

    <strong>Delete Account</strong>

    <a href="{{ route('dashboard') }}">
        Dashboard
    </a>

</div>

<div class="container">

    <div class="card">

        <h1>Delete Account</h1>

        <div class="danger">

            <strong>Warning:</strong>

            This action permanently deletes your account
            and cannot be undone.

        </div>

        @if($errors->any())

            <div class="danger">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif

        <form
            method="POST"
            action="{{ route('security.delete-account.destroy') }}"
            onsubmit="
                return confirm(
                    'Are you absolutely sure you want to delete your account?'
                );
            "
        >

            @csrf

            @method('DELETE')

            <label>
                Confirm your password
            </label>

            <input
                type="password"
                name="password"
                required
            >

            <button type="submit">
                🗑️ Permanently Delete Account
            </button>

        </form>

        <a
            class="back"
            href="{{ route('dashboard') }}"
        >
            ← Cancel
        </a>

    </div>

</div>

</body>
</html>