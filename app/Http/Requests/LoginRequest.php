<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = $this->string('identifier')->trim()->toString();
        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('name', $identifier)
            ->first();

        if (! $user?->is_active || ! Auth::attempt([
            'email' => $user->email,
            'password' => $this->string('password')->toString(),
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), 60);

            throw ValidationException::withMessages([
                'identifier' => 'Data login tidak sesuai.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'identifier' => 'Terlalu banyak percobaan masuk. Coba lagi dalam ' . RateLimiter::availableIn($this->throttleKey()) . ' detik.',
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('identifier')->toString()) . '|' . $this->ip());
    }
}
