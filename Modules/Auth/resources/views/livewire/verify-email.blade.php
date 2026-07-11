<div class="auth-card-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <img src="{{ asset('admin-assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}" width="40" height="40">
            <h1 class="auth-title">Verify Email</h1>
        </div>

        <p class="text-muted">
            A verification link has been sent to your email address.
            Please check your inbox.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-outline w-full">
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-link w-full text-muted">
                Log out
            </button>
        </form>
    </div>
</div>
