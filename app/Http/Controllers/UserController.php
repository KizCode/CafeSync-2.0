<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('admin.users.index', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('admin.users.show', compact('user'));
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        return view('admin.users.create', ['roles' => $this->roles()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return to_route('admin.users.index')->with('status', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('admin.users.edit', ['user' => $user, 'roles' => $this->roles()]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $attributes = $request->validated();

        if (blank($attributes['password'] ?? null)) {
            unset($attributes['password']);
        }

        $user->update($attributes);

        return to_route('admin.users.index')->with('status', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return to_route('admin.users.index')->with('status', 'User berhasil dihapus.');
    }

    /** @return array<string, string> */
    private function roles(): array
    {
        return [
            'owner' => 'Owner',
            'admin' => 'Admin / Manager',
            'kasir' => 'Kasir',
            'gudang' => 'Staff Gudang',
        ];
    }
}
