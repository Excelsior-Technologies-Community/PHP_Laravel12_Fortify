<!DOCTYPE html>
<html>

<head>

    <title>Change Password</title>

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
            max-width: 650px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        button {
            background: #667eea;
            color: white;
            border: 0;
            padding: 12px 20px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 14px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 14px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .strength {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .weak {
            color: #dc3545;
        }

        .medium {
            color: #fd7e14;
        }

        .strong {
            color: #198754;
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

    <strong>Change Password</strong>

    <a href="{{ route('dashboard') }}">
        Dashboard
    </a>

</div>

<div class="container">

    <div class="card">

        <h1>Change Password</h1>

        <p>
            Choose a strong password for your account.
        </p>

        @if(session('success'))

            <div class="success">
                {{ session('success') }}
            </div>

        @endif

        @if($errors->any())

            <div class="error">

                @foreach($errors->all() as $error)

                    <div>{{ $error }}</div>

                @endforeach

            </div>

        @endif

        <form
            method="POST"
            action="{{ route('security.password.update') }}"
        >

            @csrf

            <label>
                Current Password
            </label>

            <input
                type="password"
                name="current_password"
                required
            >

            <label>
                New Password
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
            >

            <div id="strength" class="strength">
                Password strength:
            </div>

            <label>
                Confirm New Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
            >

            <button type="submit">
                Change Password
            </button>

        </form>

        <a
            class="back"
            href="{{ route('dashboard') }}"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>

<script>

const password = document.getElementById('password');
const strength = document.getElementById('strength');

password.addEventListener('input', function () {

    const value = password.value;

    let score = 0;

    if (value.length >= 8) {
        score++;
    }

    if (/[A-Z]/.test(value)) {
        score++;
    }

    if (/[a-z]/.test(value)) {
        score++;
    }

    if (/[0-9]/.test(value)) {
        score++;
    }

    if (/[^A-Za-z0-9]/.test(value)) {
        score++;
    }

    strength.className = 'strength';

    if (score <= 2) {

        strength.textContent =
            'Password strength: Weak';

        strength.classList.add('weak');

    } else if (score <= 4) {

        strength.textContent =
            'Password strength: Medium';

        strength.classList.add('medium');

    } else {

        strength.textContent =
            'Password strength: Strong';

        strength.classList.add('strong');

    }

});

</script>

</body>
</html>