<div class="w-full max-w-lg mx-auto">

    {{-- Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white mb-4">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900">My Profile</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your account information</p>
    </div>

    {{-- ── Profile information card ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-6">

        <h2 class="text-base font-semibold text-gray-900 mb-5">Account Information</h2>

        @if ($profileSaved)
            <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700" role="alert" aria-live="polite">
                Profile updated successfully.
            </div>
        @endif

        <form wire:submit="updateProfile" novalidate>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    autocomplete="name"
                    required
                    class="block w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    required
                    class="block w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                >
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 transition"
                >
                    <span wire:loading.remove wire:target="updateProfile">Save changes</span>
                    <span wire:loading wire:target="updateProfile">Saving…</span>
                </button>

                <a href="{{ route('landing') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to home
                </a>
            </div>

        </form>
    </div>

    {{-- ── Change password card ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

        <h2 class="text-base font-semibold text-gray-900 mb-5">Change Password</h2>

        @if ($passwordSaved)
            <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700" role="alert" aria-live="polite">
                Password updated successfully.
            </div>
        @endif

        <form wire:submit="updatePassword" novalidate>

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                <input
                    id="current_password"
                    type="password"
                    wire:model="current_password"
                    autocomplete="current-password"
                    required
                    class="block w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('current_password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                >
                @error('current_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    autocomplete="new-password"
                    required
                    class="block w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                >
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>

            <div class="mt-6">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2 disabled:opacity-60 transition"
                >
                    <span wire:loading.remove wire:target="updatePassword">Update password</span>
                    <span wire:loading wire:target="updatePassword">Updating…</span>
                </button>
            </div>

        </form>
    </div>

</div>
