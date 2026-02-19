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
