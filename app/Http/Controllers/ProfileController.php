<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => request()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $attributes = $request->safe()->only(['name', 'username', 'email', 'password']);

        if (blank($attributes['password'] ?? null)) {
            unset($attributes['password']);
        }

        $request->user()->update($attributes);

        return to_route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }
}
