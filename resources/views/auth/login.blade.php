<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-brand-surface px-4 py-4">
        <div class="w-full max-w-md">
            <div class="rounded-3xl bg-white/70 border border-black/10 shadow-sm overflow-hidden">
                <div class="px-6 pt-8 pb-6 text-center">
                    <div class="mx-auto h-16 w-16 rounded-2xl bg-white border border-black/10 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('logo.png') }}" alt="FOUR" class="h-12 w-12 object-contain">
                    </div>

                    <h1 class="mt-4 text-2xl font-bold text-brand-dark">Seller Login</h1>
                    <p class="mt-1 text-sm opacity-70">FOUR Cafe & Coffee</p>
                </div>

                <div class="px-6 pb-6">
                    @if (session('status'))
                        <div class="mb-4 rounded-xl bg-green-100 p-3 text-green-900 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label class="text-sm font-semibold">Email</label>
                            <div class="mt-1 flex items-center gap-2 rounded-xl border border-black/10 bg-white px-3 py-2">
                                {{-- icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 9h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                       class="w-full border-0 bg-transparent p-0 focus:ring-0 focus:outline-none"
                                       placeholder="email@contoh.com">
                            </div>
                            @error('email')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="text-sm font-semibold">Password</label>
                            <div class="mt-1 flex items-center gap-2 rounded-xl border border-black/10 bg-white px-3 py-2">
                                {{-- icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-12V7a4 4 0 00-8 0v2"/>
                                </svg>
                                <input id="password" name="password" type="password" required
                                       class="w-full border-0 bg-transparent p-0 focus:ring-0 focus:outline-none"
                                       placeholder="••••••••">
                            </div>
                            @error('password')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center gap-2 text-sm">
                                <input type="checkbox" name="remember"
                                       class="rounded border-black/20 text-brand-primary focus:ring-brand-primary">
                                <span>Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-semibold text-brand-primary hover:underline"
                                   href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                                class="w-full h-11 rounded-xl bg-brand-primary text-brand-surface font-semibold hover:opacity-90">
                            Masuk
                        </button>
                    </form>

                    {{-- Divider
                    <div class="my-6 flex items-center gap-3">
                        <div class="h-px flex-1 bg-black/10"></div>
                        <div class="text-xs opacity-60">atau</div>
                        <div class="h-px flex-1 bg-black/10"></div>
                    </div>

                    {{-- Google login button
                    <a href="{{ route('auth.google.redirect') }}"
                       class="w-full h-11 rounded-xl border border-black/10 bg-white flex items-center justify-center gap-2 font-semibold hover:bg-black/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 48 48">
                            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.658 29.243 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.066 6.053 29.243 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
                            <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 16.108 19.001 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.066 6.053 29.243 4 24 4c-7.682 0-14.344 4.337-17.694 10.691z"/>
                            <path fill="#4CAF50" d="M24 44c5.141 0 9.86-1.973 13.409-5.182l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.223 0-9.626-3.321-11.287-7.946l-6.52 5.02C9.505 39.556 16.227 44 24 44z"/>
                            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.266-2.231 4.194-4.094 5.58l.002-.001 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/>
                        </svg>
                        Masuk dengan Google
                    </a>--}}

                    <div class="mt-6 text-center text-xs opacity-60">
                        Powered by FOUR Cafe & Coffee
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
