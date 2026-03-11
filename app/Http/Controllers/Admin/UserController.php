<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.admin.users.index', [
            'users' => User::with('role')->orderBy('username')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')],
            'email'    => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', Password::min(8)->uncompromised(), 'confirmed'],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Gebruiker succesvol aangemaakt.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Je kunt je eigen account niet verwijderen.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Gebruiker succesvol verwijderd.');
    }
}
