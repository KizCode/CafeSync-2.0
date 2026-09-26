<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.register', [
            'step' => 1,
            'details' => $request->session()->get('registration', []),
        ]);
    }

    public function storeDetails(Request $request): RedirectResponse
    {
        $details = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $request->session()->put('registration', $details);

        return to_route('register.password');
    }

    public function createPassword(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('registration')) {
            return to_route('register');
        }

        return view('auth.register', [
            'step' => 2,
            'details' => $request->session()->get('registration'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $details = $request->session()->get('registration');

        if (! is_array($details)) {
            return to_route('register');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = User::query()->create([
            'name' => $details['name'],
            'email' => $details['email'],
            'username' => $this->uniqueUsername($details['email']),
            'password' => $validated['password'],
            'role' => 'kasir',
            'is_active' => true,
        ]);

        $request->session()->forget('registration');

        Auth::login($user);
        $request->session()->regenerate();

        return to_route($user->homeRoute());
    }

    private function uniqueUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '_');

        if ($base === '') {
            $base = 'user';
        }

        $username = $base;
        $suffix = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base.$suffix;
            $suffix++;
        }

        return $username;
    }
}
