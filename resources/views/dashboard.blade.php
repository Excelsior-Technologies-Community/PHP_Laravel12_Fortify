<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, sans-serif;
        }

        body{
            height:100vh;
            background:linear-gradient(135deg,#667eea,#764ba2);
            display:flex;
            flex-direction:column;
        }

        /* NAVBAR */
        .navbar{
            background:rgba(255,255,255,0.15);
            backdrop-filter:blur(10px);
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:white;
        }

        .logout-btn{
            background:white;
            color:#667eea;
            border:none;
            padding:8px 16px;
            border-radius:6px;
            cursor:pointer;
            font-weight:bold;
        }

        /* CONTENT */
        .container{
            flex:1;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .card{
            background:white;
            padding:50px;
            width:400px;
            text-align:center;
            border-radius:14px;
            box-shadow:0 20px 40px rgba(0,0,0,0.2);
        }

        .avatar{
            width:70px;
            height:70px;
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:white;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:26px;
            font-weight:bold;
            margin:0 auto 20px;
        }

        h1{
            margin-bottom:10px;
            color:#333;
        }

        .welcome{
            color:#666;
            margin-bottom:25px;
        }

        .badge{
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:white;
            padding:10px 22px;
            border-radius:25px;
            display:inline-block;
            font-size:14px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h3>My Dashboard</h3>

    <form method="POST" action="/logout">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
</div>

<!-- CONTENT -->
<div class="container">
    <div class="card">

        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>

        <h1>Dashboard</h1>

        <p class="welcome">
            Welcome, <strong>{{ auth()->user()->name }}</strong>
        </p>

        <div class="badge">
            Logged In Successfully 
        </div>

    </div>
</div>

</body>
</html>
