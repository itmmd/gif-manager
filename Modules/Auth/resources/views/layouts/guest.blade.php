<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Tailwind CSS (main app build — no admin-assets) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire styles --}}
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    <main class="flex min-h-screen items-center justify-center px-4 py-12">
        {{ $slot }}
    </main>

    {{-- Livewire scripts --}}
    @livewireScripts

</body>
</html>
