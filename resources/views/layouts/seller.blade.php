<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Seller - FOUR' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <meta name="theme-color" content="#a27029">
</head>

<body class="min-h-screen bg-brand-surface text-brand-dark">
@php
    $links = [
        ['Dashboard','seller.dashboard','seller.dashboard'],
        ['Orders','seller.orders.index','seller.orders.*'],
        ['Products','seller.products.index','seller.products.*'],
        ['Categories','seller.categories.index','seller.categories.*'],
        ['Options','seller.options.index','seller.options.*'],
        ['Reports','seller.reports.index','seller.reports.*'],
        ['Settings','seller.settings.edit','seller.settings.*'],
    ];
@endphp

<div x-data="{ open: false }" class="min-h-screen">
    {{-- TOP BAR --}}
    <header class="sticky top-0 z-50 bg-brand-primary text-brand-surface shadow">
        <div class="mx-auto max-w-7xl px-3 sm:px-6">
            <div class="flex h-16 items-center justify-between gap-2">

                {{-- BRAND --}}
                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 font-semibold">
                    <img src="{{ asset('logo.png') }}" alt="FOUR" class="h-10 w-10 rounded-xl object-cover bg-white/10">
                    <span class="hidden sm:block">FOUR Cafe & Coffee</span>
                </a>

                {{-- DESKTOP NAV --}}
                <nav class="hidden lg:flex items-center gap-1">
                    @foreach($links as [$label, $route, $active])
                        <a href="{{ route($route) }}"
                           class="px-3 py-2 rounded-lg hover:bg-brand-surface/15 {{ request()->routeIs($active) ? 'bg-brand-surface/15' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>

                {{-- RIGHT ACTIONS --}}
                <div class="flex items-center gap-2">
                    {{-- Logout desktop --}}
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button class="px-3 py-2 rounded-lg bg-brand-dark/20 hover:bg-brand-dark/30">
                            Logout
                        </button>
                    </form>

                    {{-- Hamburger mobile/tablet --}}
                    <button
                        type="button"
                        class="lg:hidden h-10 w-10 grid place-items-center rounded-lg bg-brand-surface/15 hover:bg-brand-surface/25"
                        @click="open = !open"
                        aria-label="Buka Menu"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- MOBILE NAV DROPDOWN --}}
            <div x-show="open" x-cloak class="lg:hidden pb-3">
                <div class="grid grid-cols-2 gap-2">
                    @foreach($links as [$label, $route, $active])
                        <a href="{{ route($route) }}"
                           class="px-3 py-2 rounded-xl bg-brand-surface/10 hover:bg-brand-surface/20
                                  {{ request()->routeIs($active) ? 'ring-2 ring-brand-surface/30' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                {{-- Logout mobile --}}
                <div class="mt-2 sm:hidden">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full h-11 rounded-xl bg-brand-dark/20 hover:bg-brand-dark/30">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="mx-auto max-w-7xl px-3 sm:px-6 py-4 sm:py-6">
        @if(session('success'))
            <div class="mb-4 rounded-xl bg-green-100 p-3 text-green-900">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-100 p-3 text-red-900">
                {{ session('error') }}
            </div>
        @endif

        {{-- validation error global --}}
        @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-100 p-3 text-red-900">
                <div class="font-semibold mb-1">Ada error:</div>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>
</div>

{{-- AlpineJS (untuk menu mobile) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
