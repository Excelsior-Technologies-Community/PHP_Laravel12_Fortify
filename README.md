# PHP_Laravel12_Fortify

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Fortify](https://img.shields.io/badge/Auth-Fortify-blue)
![Status](https://img.shields.io/badge/Authentication-Complete-brightgreen)

---

## Overview

This project demonstrates a complete authentication system built using **Laravel 12** and **Laravel Fortify**. It includes user registration, login, logout functionality, protected routes, and a simple dashboard interface with custom Blade UI.

---

## Features

* User Registration
* User Login
* Logout Functionality
* Authenticated Dashboard
* Route Protection using `auth` middleware
* Custom Blade UI (Login / Register / Dashboard)
* Laravel 12 Compatible Setup

---

## Folder Structure

```
resources/
└── views/
    ├── auth/
    │   ├── login.blade.php
    │   └── register.blade.php
    └── dashboard.blade.php

app/
└── Providers/
    └── FortifyServiceProvider.php

config/
└── fortify.php

routes/
└── web.php
```

---

## Step 1 — Create Laravel 12 Project

```bash
composer create-project laravel/laravel fortify-app

php artisan serve
```

Open:

```
http://127.0.0.1:8000
```

---

## Step 2 — Database Configuration

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## Step 3 — Install Laravel Fortify

```bash
composer require laravel/fortify
```

---

## Step 4 — Install Fortify Files

```bash
php artisan fortify:install
```

---

## Step 5 — Run Migrations

```bash
php artisan migrate
```

Tables created:

* users
* password_reset_tokens
* sessions

---

## Step 6 — Configure Fortify

Open:

```
config/fortify.php
```

```php
<?php

use Laravel\Fortify\Features;

return [

    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'lowercase_usernames' => true,

    'home' => '/dashboard',

    'prefix' => '',

    'domain' => null,

    'middleware' => ['web'],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],

    'views' => true,

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::emailVerification(),
    ],

];
```

---

## Step 7 — Register Fortify Views

Open:

```
app/Providers/FortifyServiceProvider.php
```

```php
<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            RedirectIfTwoFactorAuthenticatable::class
        );

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', [
                'request' => $request
            ]);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) .
                '|' .
                $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );
        });
    }
}
```

---

## Step 8 — Routes

`routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

// Redirect root URL to login page
Route::get('/', function () {
    return redirect('/login');
});

// Routes accessible only for authenticated users
Route::middleware(['auth'])->group(function () {

    // Dashboard page after successful login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});
```

---

## Step 9 — Login View

Create:

```
resources/views/auth/login.blade.php
```

```blade
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
```

---

## Step 10 — Register View

Create:

```
resources/views/auth/register.blade.php
```

```blade
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
```

---

## Step 11 — Dashboard View

Create:

```
resources/views/dashboard.blade.php
```

```blade
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

<div class="navbar">
    <h3>My Dashboard</h3>

    <form method="POST" action="/logout">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
</div>

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
```

---

## Step 12 — Test Application

**Register**

```
http://127.0.0.1:8000/register
```
<img width="628" height="585" alt="Screenshot 2026-02-19 164451" src="https://github.com/user-attachments/assets/56ca1ac4-089c-48d6-ae47-a46d28ccc929" />


**Login**

```
http://127.0.0.1:8000/login
```
<img width="578" height="469" alt="Screenshot 2026-02-19 164423" src="https://github.com/user-attachments/assets/192f39f5-9e78-4143-9b8f-74e23ca5b65c" />


**Dashboard**

```
http://127.0.0.1:8000/dashboard
```
<img width="1919" height="947" alt="Screenshot 2026-02-19 165037" src="https://github.com/user-attachments/assets/8d7286f0-2496-4fd6-8929-3fc5ea99cd82" />

---

## Authentication Flow

```
Register → Auto Login → Dashboard → Logout → Login Page
```

