<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core — Livewire Test</title>
    @livewireStyles
</head>
<body>
    <h1>Livewire Test — Core Module</h1>
    @livewire(\Modules\Core\Http\Livewire\Ping::class)
    @livewireScripts
</body>
</html>
