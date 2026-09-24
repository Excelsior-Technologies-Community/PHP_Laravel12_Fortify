<!DOCTYPE html>
<html>
<head>

    <title>Profile Management</title>

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
            max-width: 700px;
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
            margin-bottom: 20px;
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

    <strong>Profile Management</strong>

    <a href="{{ route('dashboard') }}">
        Dashboard
    </a>

</div>

<div class="container">

    <div class="card">

        <h1>My Profile</h1>

        <p>
            Update your account information.
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
            action="{{ route('security.profile.update') }}"
        >

            @csrf

            <label>
                Full Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
            >

            <label>
                Email Address
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
            >

            <button type="submit">
                Update Profile
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

</body>
</html>