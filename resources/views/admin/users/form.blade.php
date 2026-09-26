<div class="field">
    <label for="name">Nama</label><input id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
        required>
</div>
<div class="field"><label for="username">Username</label><input id="username" name="username"
        value="{{ old('username', $user->username ?? '') }}" required></div>
<div class="field"><label for="email">Email</label><input id="email" name="email" type="email"
        value="{{ old('email', $user->email ?? '') }}" required></div>
<div class="field"><label for="role">Role</label><select id="role" name="role" required>
        @foreach ($roles as $value => $label)
            <option value="{{ $value }}" @selected(old('role', $user->role ?? 'kasir') === $value)>{{ $label }}</option>
        @endforeach
    </select></div>
<div class="field"><label for="password">Password
        {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label><input id="password" name="password"
        type="password" {{ isset($user) ? '' : 'required' }} minlength="8"></div>
<div class="field"><label for="password_confirmation">Konfirmasi password</label><input id="password_confirmation"
        name="password_confirmation" type="password" {{ isset($user) ? '' : 'required' }} minlength="8"></div>
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))> User aktif</label>
