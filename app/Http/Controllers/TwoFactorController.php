<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    /**
     * Display the two-factor authentication manager.
     */
    public function index(Request $request)
    {
        return view('security.two-factor', [
            'user' => $request->user(),
        ]);
    }
}