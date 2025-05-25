<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>

<body class="min-h-screen">
    <x-header />

    <main class="container mx-auto py-4">
        {{ $slot }}
    </main>

    @fluxScripts
</body>

</html>