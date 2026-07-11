<div class="auth-card-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <img src="{{ asset('admin-assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}" width="40" height="40">
            <h1 class="auth-title">{{ config('app.name') }}</h1>
            <p class="auth-subtitle">Sign in to your account</p>
        </div>

        @if ($errorMessage)
            <div class="alert alert-danger" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        @if (request()->has('reset'))
            <div class="alert alert-success" role="alert">
                Your password has been reset. Please sign in.
            </div>
        @endif

        <form wire:submit="login" novalidate>
            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input
                    id="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    wire:model="email"
                    autocomplete="email"
                    autofocus
                    required
                />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    wire:model="password"
                    autocomplete="current-password"
                    required
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input id="remember" type="checkbox" class="form-check-input" wire:model="remember">
                    <label for="remember" class="form-check-label">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-muted small">Forgot password?</a>
                @endif
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Sign in</span>
                    <span wire:loading>Signing in…</span>
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <p class="auth-footer-text mt-4 text-center">
                Don't have an account?
                <a href="{{ route('register') }}">Create one</a>
            </p>
        @endif
    </div>
</div>
