<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'FOUR Cafe & Coffee' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <meta name="theme-color" content="#a27029">
</head>
<body class="bg-brand-surface text-brand-dark">
    <header class="sticky top-0 z-40 bg-brand-surface/95 backdrop-blur border-b border-black/10">
        <div class="mx-auto max-w-3xl px-4 py-3 flex items-center justify-between">
            <a href="{{ route('customer.menu') }}" class="font-semibold flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="FOUR" class="h-8 w-8 rounded-lg object-cover">
                <span><span class="text-brand-primary">FOUR</span> Cafe & Coffee</span>
            </a>
            <a href="{{ route('customer.cart.show') }}" class="px-3 py-2 rounded-lg bg-brand-primary text-brand-surface">
                Keranjang
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-5">
        @if(session('success'))
            <div class="mb-4 rounded-xl bg-green-100 p-3 text-green-900">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-100 p-3 text-red-900">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
