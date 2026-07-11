<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="{{ asset('admin-assets/images/favicon.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Gentelella CSS --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/main-v4-DDS6x4g-.css') }}">

    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.setAttribute('data-theme', t || (d ? 'dark' : 'light'));
            } catch (e) {}
        })();
    </script>

    @livewireStyles
</head>
<body data-shell="auth">

<div class="auth-page">
    {{ $slot }}
</div>

@livewireScripts

</body>
</html>
