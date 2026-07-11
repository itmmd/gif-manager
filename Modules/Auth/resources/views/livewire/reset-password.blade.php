<div class="auth-card-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <img src="{{ asset('admin-assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}" width="40" height="40">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your new password</p>
        </div>

        @if ($errorMessage)
            <div class="alert alert-danger" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        <form wire:submit="resetPassword" novalidate>
            <input type="hidden" wire:model="token">

            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input
                    id="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    wire:model="email"
                    autocomplete="email"
                    required
                />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password" class="form-label">New password</label>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    wire:model="password"
                    autocomplete="new-password"
                    autofocus
                    required
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password_confirmation" class="form-label">Confirm new password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    class="form-control"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    required
                />
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Reset password</span>
                    <span wire:loading>Resetting…</span>
                </button>
            </div>
        </form>
    </div>
</div>
