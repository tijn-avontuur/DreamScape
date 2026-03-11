<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('pages.profile.show', [
            'user' => $request->user()->load('role'),
        ]);
    }
}
