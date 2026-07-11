<div class="auth-card-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <img src="{{ asset('admin-assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}" width="40" height="40">
            <h1 class="auth-title">Forgot Password</h1>
            <p class="auth-subtitle">Enter your email to receive a reset link</p>
        </div>

        @if ($status)
            <div class="alert alert-success" role="alert">
                {{ $status }}
            </div>
        @endif

        @if ($errorMessage)
            <div class="alert alert-danger" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        <form wire:submit="sendResetLink" novalidate>
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

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Send reset link</span>
                    <span wire:loading>Sending…</span>
                </button>
            </div>
        </form>

        <p class="auth-footer-text mt-4 text-center">
            <a href="{{ route('login') }}">← Back to sign in</a>
        </p>
    </div>
</div>
