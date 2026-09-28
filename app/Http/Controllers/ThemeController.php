<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,system',
        ]);

        $user = $request->user();
        $user->theme_preference = $request->theme;
        $user->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $request->theme]);
        }

        return redirect()->back()->with('success', "Theme preference updated to '{$request->theme}'.");
    }
}
