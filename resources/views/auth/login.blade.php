@extends('layouts.auth')
@section('content')
    <h2>Secure Login</h2>
    <p class="muted mt-0">Sign in to open your dashboard, profile, and role workspace.</p>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.attempt', absolute: false) }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <div class="password-input-wrap">
                <input id="password" type="password" name="password" required>
                <button
                    type="button"
                    class="password-toggle"
                    data-password-toggle
                    data-visible="false"
                    aria-label="Show password"
                    aria-pressed="false"
                    aria-controls="password"
                    title="Show password"
                >
                    <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.77 21.77 0 0 1 5.06-7.94"></path>
                        <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-2.16 3.19"></path>
                        <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
        </div>
        <div class="field remember-row">
            <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <label for="remember">Remember me</label>
        </div>
        <button class="btn" type="submit">Login</button>
    </form>
    <script src="/js/login-password-toggle.js" defer></script>

@endsection
