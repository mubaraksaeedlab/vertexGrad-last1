<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('backend.auth_layout.default_title'))</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />

    @stack('auth_styles')

    <style>
        :root {
            --auth-bg: #f5f7fb;
            --auth-card: #ffffff;
            --auth-text: #172033;
            --auth-soft: #7b8497;
            --auth-border: #e8ecf4;
            --auth-primary: #1b00ff;
            --auth-primary-soft: rgba(27, 0, 255, 0.08);
            --auth-shadow: 0 8px 20px rgba(18, 38, 63, 0.06);
        }

        html[dir="rtl"] body {
            text-align: right;
        }

        body {
            background: var(--auth-bg);
            font-family: 'Inter', sans-serif;
        }

        .auth-page-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-header {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--auth-border);
            box-shadow: 0 4px 16px rgba(18, 38, 63, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .auth-header-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .auth-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .auth-brand img {
            max-height: 46px;
            width: auto;
        }

        .auth-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .auth-brand-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--auth-text);
            margin: 0;
        }

        .auth-brand-subtitle {
            font-size: 0.78rem;
            color: var(--auth-soft);
            margin-top: 2px;
        }

        .auth-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .auth-link-btn,
        .auth-lang-btn {
            min-height: 42px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid var(--auth-border);
            background: #fff;
            color: var(--auth-text);
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .2s ease;
        }

        .auth-link-btn:hover,
        .auth-lang-btn:hover {
            text-decoration: none;
            color: var(--auth-primary);
            border-color: #d9def0;
            background: #fafcff;
        }

        .auth-lang-btn.is-active {
            background: var(--auth-primary-soft);
            color: var(--auth-primary);
            border-color: rgba(27, 0, 255, 0.15);
        }

        .auth-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .auth-footer {
            margin-top: auto;
            border-top: 1px solid var(--auth-border);
            background: #fff;
            padding: 16px 20px;
            text-align: center;
            color: var(--auth-soft);
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .auth-header-inner {
                min-height: auto;
                padding-top: 14px;
                padding-bottom: 14px;
                flex-direction: column;
                align-items: stretch;
            }

            .auth-actions {
                justify-content: center;
            }

            .auth-brand {
                justify-content: center;
            }

            .auth-brand-text {
                text-align: center;
            }
        }
    </style>
</head>
<body class="@yield('body_class', 'auth-page')">
    <div class="auth-page-shell">
        <header class="auth-header">
            <div class="container-fluid">
                <div class="auth-header-inner">
                    <a href="{{ route('admin.login.show') }}" class="auth-brand">
                        <img src="{{ asset('vendors/images/VertexGrad_logoud.png') }}" alt="{{ __('backend.auth_layout.logo_alt') }}">
                        <div class="auth-brand-text">
                            <div class="auth-brand-title">{{ __('backend.auth_layout.brand_title') }}</div>
                            <div class="auth-brand-subtitle">{{ __('backend.auth_layout.brand_subtitle') }}</div>
                        </div>
                    </a>

                    <div class="auth-actions">
                        <a href="{{ route('admin.language.switch', 'en') }}"
                           class="auth-lang-btn {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">
                            EN
                        </a>

                        <a href="{{ route('admin.language.switch', 'ar') }}"
                           class="auth-lang-btn {{ app()->getLocale() === 'ar' ? 'is-active' : '' }}">
                            عربي
                        </a>

                        @yield('auth_actions')
                    </div>
                </div>
            </div>
        </header>

        <main class="auth-main">
            @yield('content')
        </main>

        <footer class="auth-footer">
            {{ __('backend.auth_layout.footer_text') }}
        </footer>
    </div>

    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>

    @stack('auth_scripts')
</body>
</html>