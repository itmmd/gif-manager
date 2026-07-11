<div class="auth-card-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <img src="{{ asset('admin-assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}" width="40" height="40">
            <h1 class="auth-title">{{ config('app.name') }}</h1>
            <p class="auth-subtitle">Create your account</p>
        </div>

        <form wire:submit="register" novalidate>
            <div class="form-group">
                <label for="name" class="form-label">Full name</label>
                <input
                    id="name"
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    wire:model="name"
                    autocomplete="name"
                    autofocus
                    required
                />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
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
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    wire:model="password"
                    autocomplete="new-password"
                    required
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password_confirmation" class="form-label">Confirm password</label>
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
                    <span wire:loading.remove>Create account</span>
                    <span wire:loading>Creating…</span>
                </button>
            </div>
        </form>

        <p class="auth-footer-text mt-4 text-center">
            Already have an account?
            <a href="{{ route('login') }}">Sign in</a>
        </p>
    </div>
</div>
