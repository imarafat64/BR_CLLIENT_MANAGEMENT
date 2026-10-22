@extends('layouts.auth')

@section('page_title', 'Create Account')

@section('content')
<div class="form-header fade-up">
    <div class="form-eyebrow">Manage Portal</div>
    <h1 class="form-title">Create Account ✨</h1>
    <p class="form-subtitle">Join us and manage your clients with ease</p>
</div>

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-error fade-up" role="alert">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
    @csrf

    {{-- Full Name --}}
    <div class="form-group fade-up">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-wrapper">
            <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <input
                id="name"
                type="text"
                name="name"
                class="form-input @error('name') is-invalid @enderror"
                placeholder="e.g. Leslie Alexander"
                value="{{ old('name') }}"
                required
                autocomplete="name"
                autofocus
            >
        </div>
        @error('name')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>

    {{-- Email --}}
    <div class="form-group fade-up">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-wrapper">
            <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </span>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input @error('email') is-invalid @enderror"
                placeholder="name@example.com"
                value="{{ old('email') }}"
                required
                autocomplete="email"
            >
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>

    {{-- Password --}}
    <div class="form-group fade-up">
        <label for="password" class="form-label">Password</label>
        <div class="input-wrapper">
            <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input @error('password') is-invalid @enderror"
                placeholder="Minimum 8 characters"
                required
                autocomplete="new-password"
                oninput="checkStrength(this.value, 'pw-bars')"
            >
            <button type="button" class="input-action" onclick="togglePassword('password', this)" aria-label="Show/hide password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>
        <!-- Password strength bars -->
        <div class="pw-strength" id="pw-bars" aria-label="Password strength indicator">
            <div class="pw-strength-bar"></div>
            <div class="pw-strength-bar"></div>
            <div class="pw-strength-bar"></div>
            <div class="pw-strength-bar"></div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="form-group fade-up">
        <label for="password-confirm" class="form-label">Confirm Password</label>
        <div class="input-wrapper">
            <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </span>
            <input
                id="password-confirm"
                type="password"
                name="password_confirmation"
                class="form-input"
                placeholder="Re-type your password"
                required
                autocomplete="new-password"
            >
            <button type="button" class="input-action" onclick="togglePassword('password-confirm', this)" aria-label="Show/hide confirm password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Terms checkbox --}}
    <div class="check-group fade-up">
        <input class="custom-checkbox" type="checkbox" name="terms" id="terms" required>
        <label class="check-label" for="terms">
            I agree to the <a href="#">Terms of Service</a> &amp; <a href="#">Privacy Policy</a>
        </label>
    </div>

    {{-- Submit --}}
    <div class="fade-up">
        <button type="submit" id="register-btn" class="btn-auth">
            <span class="btn-spinner"></span>
            <span class="btn-text">Create Account</span>
        </button>
    </div>
</form>

<div class="auth-footer fade-up">
    Already have an account?
    <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
