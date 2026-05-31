<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'stock_threshold' => 'required|integer|min:1|max:10000',
        ]);

        auth()->user()->update([
            'stock_threshold' => $request->stock_threshold,
        ]);

        return back()->with('success', 'Settings saved.');
    }
}