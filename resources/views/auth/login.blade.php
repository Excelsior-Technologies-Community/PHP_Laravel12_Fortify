<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,#11998e,#38ef7d);
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
            border-color:#11998e;
        }

        button{
            width:100%;
            padding:12px;
            background:#11998e;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#0f7f77;
        }

        .link{
            text-align:center;
            margin-top:15px;
        }

        a{
            text-decoration:none;
            color:#11998e;
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
    <h2>Welcome Back</h2>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <div class="link">
        Don't have account? <a href="/register">Register</a>
    </div>
</div>

</body>
</html>
