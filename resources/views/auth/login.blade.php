<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Login - FOUR Cafe & Coffee</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --coffee: #B27A2A;
            --coffee-dark: #8A5C1D;
            --green: #1F3B36;
            --cream: #F5F1E8;
            --text: #1F1A14;
            --muted: #746A5F;
            --line: #E7DCCB;
            --input: #FFFCF7;
            --white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100dvh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: var(--cream);
            color: var(--text);
        }

        .auth-page {
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(16px, 4vw, 40px);
            background:
                radial-gradient(circle at top left, rgba(178, 122, 42, 0.14), transparent 34%),
                radial-gradient(circle at bottom right, rgba(31, 59, 54, 0.13), transparent 36%),
                var(--cream);
        }

        .auth-shell {
            width: min(100%, 460px);
        }

        .auth-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid var(--line);
            border-radius: clamp(22px, 5vw, 32px);
            padding: clamp(22px, 5vw, 34px);
            box-shadow: 0 24px 70px rgba(88, 63, 34, 0.16);
        }

        .brand {
            text-align: center;
            margin-bottom: clamp(20px, 4vw, 28px);
        }

        .logo-wrap {
            width: clamp(60px, 14vw, 72px);
            height: clamp(60px, 14vw, 72px);
            border-radius: clamp(18px, 4vw, 24px);
            margin: 0 auto clamp(12px, 3vw, 16px);
            background: var(--white);
            border: 1px solid var(--line);
            box-shadow: 0 12px 30px rgba(88, 63, 34, 0.14);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-wrap img {
            width: clamp(44px, 10vw, 52px);
            height: clamp(44px, 10vw, 52px);
            object-fit: contain;
        }

        .brand-kicker {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border-radius: 999px;
            background: #F9F4EA;
            border: 1px solid var(--line);
            color: var(--coffee);
            font-size: clamp(10px, 2.2vw, 11px);
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: clamp(10px, 2.6vw, 14px);
            white-space: nowrap;
        }

        .auth-title {
            margin: 0;
            color: var(--text);
            font-size: clamp(24px, 5.7vw, 30px);
            line-height: 1.12;
            font-weight: 900;
            letter-spacing: -0.035em;
        }

        .auth-subtitle {
            margin: 10px auto 0;
            max-width: 340px;
            color: var(--muted);
            font-size: clamp(13px, 3vw, 14px);
            line-height: 1.6;
            font-weight: 500;
        }

        .status-box {
            margin-bottom: 18px;
            padding: 13px 15px;
            border-radius: 18px;
            background: #ECFDF3;
            border: 1px solid #BBF7D0;
            color: #166534;
            font-size: 14px;
            font-weight: 650;
        }

        .form {
            margin-top: clamp(20px, 4vw, 26px);
        }

        .field {
            margin-bottom: clamp(14px, 3.2vw, 18px);
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #2A221B;
            font-size: 13px;
            font-weight: 850;
        }

        .input-box {
            height: clamp(50px, 10vw, 54px);
            border-radius: clamp(15px, 3.6vw, 18px);
            border: 1px solid var(--line);
            background: var(--input);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 15px;
            transition: 0.18s ease;
        }

        .input-box:focus-within {
            border-color: var(--coffee);
            background: var(--white);
            box-shadow: 0 0 0 5px rgba(178, 122, 42, 0.12);
        }

        .input-box svg {
            width: 20px;
            height: 20px;
            color: #8A8176;
            flex-shrink: 0;
        }

        .input-box:focus-within svg {
            color: var(--coffee);
        }

        .input-box input {
            width: 100%;
            height: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--text);
            font-size: 14px;
            font-weight: 650;
        }

        .input-box input::placeholder {
            color: #A69B90;
            font-weight: 500;
        }

        .error-text {
            margin: 8px 0 0;
            color: #DC2626;
            font-size: 13px;
            font-weight: 650;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin: 2px 0 clamp(18px, 4vw, 22px);
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 650;
            white-space: nowrap;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--coffee);
        }

        .link {
            color: var(--coffee);
            font-size: 13px;
            font-weight: 850;
            text-decoration: none;
            white-space: nowrap;
        }

        .link:hover {
            color: var(--coffee-dark);
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            height: clamp(50px, 10vw, 54px);
            border: 0;
            border-radius: clamp(15px, 3.6vw, 18px);
            background: linear-gradient(135deg, var(--coffee), #C58A37);
            color: #ffffff;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 16px 34px rgba(178, 122, 42, 0.32);
            transition: 0.18s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, var(--coffee-dark), var(--coffee));
            box-shadow: 0 20px 42px rgba(178, 122, 42, 0.38);
        }

        .powered {
            margin-top: clamp(16px, 4vw, 22px);
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }

        .powered strong {
            color: var(--coffee);
            font-weight: 850;
        }

        @media (max-width: 360px) {
            .auth-page {
                padding: 12px;
                align-items: flex-start;
            }

            .auth-card {
                padding: 20px 16px;
            }

            .brand-kicker {
                letter-spacing: 0.09em;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (min-width: 361px) and (max-width: 480px) {
            .auth-page {
                padding: 16px 14px;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (min-width: 481px) and (max-width: 768px) {
            .auth-shell {
                width: min(100%, 440px);
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .auth-shell {
                width: min(100%, 470px);
            }
        }

        @media (min-width: 1440px) {
            .auth-shell {
                max-width: 480px;
            }
        }

        @media (max-height: 680px) and (orientation: landscape) {
            .auth-page {
                align-items: flex-start;
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .brand {
                margin-bottom: 16px;
            }

            .logo-wrap {
                width: 56px;
                height: 56px;
                border-radius: 18px;
                margin-bottom: 10px;
            }

            .logo-wrap img {
                width: 42px;
                height: 42px;
            }

            .auth-card {
                padding: 20px;
            }

            .form {
                margin-top: 18px;
            }
        }
    
/* Tablet / iPad portrait */
@media (min-width: 700px) and (max-width: 1180px) and (orientation: portrait) {
    .auth-page {
        align-items: flex-start;
        padding-top: clamp(48px, 7vh, 86px);
        padding-bottom: 40px;
    }

    .auth-shell {
        width: min(100%, 520px);
        max-width: 520px;
    }

    .auth-card {
        padding: clamp(30px, 4.5vw, 42px);
        border-radius: 34px;
    }

    .logo-wrap {
        width: 78px;
        height: 78px;
        border-radius: 26px;
    }

    .logo-wrap img {
        width: 56px;
        height: 56px;
    }

    .auth-title {
        font-size: clamp(30px, 4.1vw, 38px);
    }

    .auth-subtitle {
        max-width: 400px;
        font-size: 15px;
    }

    .input-box,
    .submit-btn {
        height: 58px;
        border-radius: 20px;
    }
}

/* Tablet / iPad landscape */
@media (min-width: 900px) and (max-width: 1366px) and (orientation: landscape) {
    .auth-page {
        align-items: center;
        padding: 32px;
    }

    .auth-shell {
        width: min(100%, 500px);
        max-width: 500px;
    }

    .auth-card {
        padding: 34px;
    }

    .auth-title {
        font-size: 32px;
    }
}

/* Desktop besar */
@media (min-width: 1367px) {
    .auth-shell {
        max-width: 480px;
    }
}
    
        /* =========================================================
           FOLD DEVICES
           - Galaxy Fold / Z Fold outer screen
           - Fold inner portrait
           - Fold inner landscape
        ========================================================= */

        /* 1. Fold cover screen / layar luar yang sangat sempit */
        @media (max-width: 319px) {
            .auth-page {
                padding: 10px;
                align-items: flex-start;
            }

            .auth-shell {
                width: 100%;
                max-width: 100%;
            }

            .auth-card {
                padding: 18px 14px;
                border-radius: 20px;
            }

            .brand {
                margin-bottom: 16px;
            }

            .logo-wrap {
                width: 54px;
                height: 54px;
                border-radius: 18px;
                margin-bottom: 10px;
            }

            .logo-wrap img {
                width: 38px;
                height: 38px;
            }

            .brand-kicker {
                font-size: 9px;
                padding: 6px 10px;
                letter-spacing: 0.08em;
                margin-bottom: 10px;
            }

            .auth-title {
                font-size: 22px;
                line-height: 1.15;
            }

            .auth-subtitle {
                max-width: 100%;
                font-size: 12px;
                line-height: 1.5;
                margin-top: 8px;
            }

            .form {
                margin-top: 16px;
            }

            .field {
                margin-bottom: 12px;
            }

            .field-label {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .input-box,
            .submit-btn {
                height: 48px;
                border-radius: 14px;
            }

            .input-box {
                padding: 0 12px;
                gap: 10px;
            }

            .input-box svg {
                width: 18px;
                height: 18px;
            }

            .input-box input {
                font-size: 13px;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                margin-bottom: 16px;
            }

            .remember,
            .link,
            .back-area .link {
                font-size: 12px;
            }

            .powered {
                margin-top: 14px;
                font-size: 11px;
            }
        }

        /* 2. Fold cover screen / outer display normal */
        @media (min-width: 320px) and (max-width: 389px) {
            .auth-page {
                padding: 12px;
                align-items: flex-start;
            }

            .auth-shell {
                width: 100%;
                max-width: 100%;
            }

            .auth-card {
                padding: 20px 16px;
                border-radius: 22px;
            }

            .brand {
                margin-bottom: 18px;
            }

            .logo-wrap {
                width: 58px;
                height: 58px;
                border-radius: 18px;
                margin-bottom: 12px;
            }

            .logo-wrap img {
                width: 40px;
                height: 40px;
            }

            .brand-kicker {
                font-size: 9.5px;
                padding: 6px 10px;
                letter-spacing: 0.09em;
            }

            .auth-title {
                font-size: 23px;
            }

            .auth-subtitle {
                max-width: 100%;
                font-size: 12px;
                line-height: 1.55;
            }

            .form {
                margin-top: 18px;
            }

            .field {
                margin-bottom: 14px;
            }

            .input-box,
            .submit-btn {
                height: 49px;
                border-radius: 15px;
            }

            .input-box {
                padding: 0 13px;
            }

            .input-box svg {
                width: 18px;
                height: 18px;
            }

            .input-box input {
                font-size: 13px;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 18px;
            }

            .remember,
            .link,
            .back-area .link {
                font-size: 12px;
            }

            .powered {
                margin-top: 14px;
                font-size: 11px;
            }
        }

        /* 3. Fold inner screen portrait / buka layar dalam */
        @media (min-width: 390px) and (max-width: 680px) and (orientation: portrait) {
            .auth-page {
                padding: 18px 16px;
                align-items: center;
            }

            .auth-shell {
                width: min(100%, 440px);
                max-width: 440px;
            }

            .auth-card {
                padding: 26px 22px;
                border-radius: 26px;
            }

            .logo-wrap {
                width: 64px;
                height: 64px;
                border-radius: 20px;
            }

            .logo-wrap img {
                width: 46px;
                height: 46px;
            }

            .brand-kicker {
                font-size: 10px;
            }

            .auth-title {
                font-size: 26px;
            }

            .auth-subtitle {
                max-width: 100%;
                font-size: 13px;
            }

            .input-box,
            .submit-btn {
                height: 50px;
                border-radius: 16px;
            }
        }

        /* 4. Fold inner landscape / tablet kecil landscape */
        @media (min-width: 681px) and (max-width: 950px) and (orientation: landscape) {
            .auth-page {
                align-items: flex-start;
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .auth-shell {
                width: min(100%, 460px);
                max-width: 460px;
            }

            .auth-card {
                padding: 22px 20px;
                border-radius: 24px;
            }

            .brand {
                margin-bottom: 16px;
            }

            .logo-wrap {
                width: 58px;
                height: 58px;
                border-radius: 18px;
                margin-bottom: 10px;
            }

            .logo-wrap img {
                width: 42px;
                height: 42px;
            }

            .brand-kicker {
                margin-bottom: 10px;
            }

            .auth-title {
                font-size: 24px;
            }

            .auth-subtitle {
                font-size: 13px;
                line-height: 1.5;
                margin-top: 8px;
            }

            .form {
                margin-top: 16px;
            }

            .field {
                margin-bottom: 12px;
            }

            .input-box,
            .submit-btn {
                height: 48px;
                border-radius: 14px;
            }

            .form-row {
                margin-bottom: 16px;
            }

            .powered {
                margin-top: 12px;
            }
        }

        /* 5. Safety untuk layar pendek */
        @media (max-height: 720px) {
            .auth-page {
                align-items: flex-start;
            }
        }

        /* =========================================================
           GALAXY Z FOLD / TALL NARROW COVER SCREEN FIX
           Example: Galaxy Z Fold 5 cover screen 344 x 882
        ========================================================= */

        @media (min-width: 320px) and (max-width: 389px) and (min-height: 760px) {
            .auth-page {
                align-items: center;
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .auth-shell {
                width: 100%;
                max-width: 344px;
            }

            .auth-card {
                padding: 22px 16px;
                border-radius: 24px;
            }

            .brand {
                margin-bottom: 18px;
            }

            .logo-wrap {
                width: 60px;
                height: 60px;
                border-radius: 19px;
                margin-bottom: 12px;
            }

            .logo-wrap img {
                width: 42px;
                height: 42px;
            }

            .brand-kicker {
                font-size: 9.5px;
                padding: 6px 10px;
                margin-bottom: 11px;
            }

            .auth-title {
                font-size: 24px;
                line-height: 1.15;
            }

            .auth-subtitle {
                font-size: 12.5px;
                line-height: 1.55;
                max-width: 290px;
            }

            .form {
                margin-top: 18px;
            }

            .field {
                margin-bottom: 14px;
            }

            .input-box,
            .submit-btn {
                height: 50px;
                border-radius: 16px;
            }

            .form-row {
                margin-bottom: 18px;
            }

            .powered {
                margin-top: 16px;
            }
        }
</style>
</head>
<body>
    <main class="auth-page">
        <section class="auth-shell">
            <div class="auth-card">
                <div class="brand">
                    <div class="logo-wrap">
                        <img src="{{ asset('logo.png') }}" alt="FOUR Cafe & Coffee">
                    </div>

                    <div class="brand-kicker">Seller Dashboard</div>

                    <h1 class="auth-title">Masuk ke akun seller</h1>

                    <p class="auth-subtitle">
                        Kelola menu, pesanan, dan aktivitas FOUR Cafe & Coffee dari satu dashboard.
                    </p>
                </div>

                @if (session('status'))
                    <div class="status-box">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf

                    <div class="field">
                        <label for="email" class="field-label">Email</label>

                        <div class="input-box">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 9h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>

                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="email@contoh.com">
                        </div>

                        @error('email')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password" class="field-label">Password</label>

                        <div class="input-box">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-12V7a4 4 0 00-8 0v2"/>
                            </svg>

                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                        </div>

                        @error('password')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit" class="submit-btn">Masuk</button>
                </form>
            </div>

            <p class="powered">
                Powered by <strong>FOUR Cafe & Coffee</strong>
            </p>
        </section>
    </main>
</body>
</html>



