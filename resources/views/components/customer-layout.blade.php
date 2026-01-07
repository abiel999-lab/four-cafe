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
    <header class="sticky top-0 z-50 bg-brand-surface/80 backdrop-blur border-b border-black/10">
        <div class="mx-auto max-w-7xl px-3 sm:px-6">
            <div class="flex h-16 items-center justify-between gap-3">
                <a href="{{ route('customer.menu') }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" alt="FOUR" class="h-10 w-10 rounded-xl object-cover">
                    <span class="font-semibold text-brand-primary hidden sm:block">FOUR Cafe & Coffee</span>
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('customer.menu') }}"
                       class="px-4 py-2 rounded-xl bg-brand-primary text-brand-surface font-semibold">
                        Keranjang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-3 sm:px-6 py-6">
        @if(session('success'))
            <div class="mb-4 rounded-xl bg-green-100 p-3 text-green-900">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-100 p-3 text-red-900">{{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
