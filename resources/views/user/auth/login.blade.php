@extends('layouts.app')

@section('content')
    <style>
        .auth-page {
            max-width: 1080px;
            margin: 0 auto;
        }

        .auth-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr);
            min-height: 640px;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid #e6d4bf;
            background: #fff8ef;
            box-shadow: 0 24px 60px rgba(92, 53, 25, 0.12);
        }

        .auth-visual {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 24px;
            padding: 42px;
            background:
                linear-gradient(180deg, rgba(28, 20, 12, 0.38) 0%, rgba(28, 20, 12, 0.58) 100%),
                url('{{ asset('image/bg.jpg') }}') center center / cover no-repeat;
            color: #fff;
        }

        .auth-visual > * {
            position: relative;
            z-index: 1;
        }

        .auth-visual-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.82);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #2d2217;
        }

        .auth-visual-copy {
            max-width: 280px;
        }

        .auth-visual-copy h2 {
            margin: 0 0 8px;
            font-size: 34px;
            line-height: 1;
            color: #fff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .auth-visual-copy p {
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.22);
        }

        .auth-form-panel {
            padding: 44px 42px;
            background: rgba(255, 250, 242, 0.96);
            display: flex;
            align-items: center;
        }

        .auth-form-card {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .auth-form-card h1 {
            margin: 0;
            font-size: 40px;
            line-height: 1;
            color: var(--brand-text);
        }

        .auth-form-card .subtitle {
            margin: 8px 0 28px;
            font-size: 17px;
            color: var(--muted);
        }

        .auth-field {
            margin-bottom: 18px;
        }

        .auth-field label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 800;
            color: #675243;
        }

        .auth-field input {
            width: 100%;
            border: none;
            border-bottom: 1.5px solid #8f7a67;
            background: transparent;
            padding: 10px 2px 12px;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .auth-field input:focus {
            border-bottom-color: var(--primary);
            box-shadow: 0 6px 0 -4px rgba(201, 106, 49, 0.35);
        }

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 42px;
        }

        .password-field input::-ms-reveal,
        .password-field input::-ms-clear {
            display: none;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            bottom: 8px;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            background: transparent;
            color: var(--primary-dark);
            cursor: pointer;
            padding: 0;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .password-toggle .eye-off-icon {
            display: none;
        }

        .password-toggle.is-visible .eye-icon {
            display: none;
        }

        .password-toggle.is-visible .eye-off-icon {
            display: block;
        }

        .auth-inline-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: -4px 0 18px;
        }

        .auth-inline-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .auth-inline-row label {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #675243;
        }

        .auth-submit {
            width: 100%;
            margin-top: 8px;
            padding: 12px 18px;
            border-radius: 4px;
            font-size: 15px;
            justify-content: center;
        }

        .auth-footnote {
            margin: 18px 0 0;
            text-align: center;
            font-size: 14px;
            color: #6d5a4a;
        }

        .auth-footnote a {
            color: #111;
            font-weight: 800;
            text-decoration: none;
        }

        .auth-footnote a:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-visual {
                min-height: 280px;
                padding: 32px 28px;
            }

            .auth-form-panel {
                padding: 34px 24px 38px;
            }

            .auth-form-card h1 {
                font-size: 32px;
            }
        }
    </style>

    <div class="auth-page">
        <div class="auth-shell">
            <section class="auth-visual" aria-hidden="true">
                <div class="auth-visual-badge">Sejucara</div>

                <div class="auth-visual-copy">
                    <h2>Welcome back</h2>
                    <p>Masuk untuk lanjut belanja produk, baca artikel, dan ikut forum komunitas Sejucara.</p>
                </div>
            </section>

            <section class="auth-form-panel">
                <div class="auth-form-card">
                    <h1>SEJUCARA</h1>
                    <p class="subtitle">Login to your account</p>

                    <form action="{{ route('login.submit') }}" method="POST" class="auth-form">
                        @csrf

                        <div class="auth-field">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required>
                        </div>

                        <div class="auth-field password-field">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
                            <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Tampilkan password">
                                <svg class="eye-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-off-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M3 3l18 18"></path>
                                    <path d="M10.6 10.6A3 3 0 0 0 13.4 13.4"></path>
                                    <path d="M9.9 4.3A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a17.8 17.8 0 0 1-3.2 4.2"></path>
                                    <path d="M6.6 6.6C3.6 8.6 2 12 2 12s3.5 8 10 8a10.8 10.8 0 0 0 4.1-.8"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="auth-inline-row">
                            <input id="remember" type="checkbox" name="remember" value="1">
                            <label for="remember">Ingat saya</label>
                        </div>

                        <button class="btn-primary auth-submit" type="submit">Login</button>
                    </form>

                    <p class="auth-footnote">
                        belum punya akun? <a href="{{ route('register') }}">Register</a>
                    </p>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.classList.toggle('is-visible', isHidden);
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });
    </script>
@endsection
