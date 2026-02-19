<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,#667eea,#764ba2);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:0;
        }

        .card{
            background:#fff;
            padding:40px;
            width:350px;
            border-radius:10px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            text-align:center;
            margin-bottom:25px;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:14px;
        }

        input:focus{
            outline:none;
            border-color:#667eea;
        }

        button{
            width:100%;
            padding:12px;
            background:#667eea;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#5563d6;
        }

        .link{
            text-align:center;
            margin-top:15px;
        }

        a{
            text-decoration:none;
            color:#667eea;
            font-weight:bold;
        }

        .error{
            color:red;
            margin-bottom:10px;
            font-size:14px;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Create Account</h2>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

        <button type="submit">Register</button>
    </form>

    <div class="link">
        Already have account? <a href="/login">Login</a>
    </div>
</div>

</body>
</html>
