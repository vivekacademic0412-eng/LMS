@php
    $appName = config('app.name', 'Academic Mantra');
    $pageTitle = trim($__env->yieldContent('title', 'System Error'));
    $status = trim($__env->yieldContent('status', 'Error'));
    $tone = trim($__env->yieldContent('tone', 'cool'));
    $pathLabel = request()->path() === '/' ? '/' : '/' . ltrim(request()->path(), '/');
    $hasDashboard = \Illuminate\Support\Facades\Route::has('dashboard');
    $hasLogin = \Illuminate\Support\Facades\Route::has('login');
    $isAuthenticated = auth()->check();
    $primaryUrl = $isAuthenticated && $hasDashboard
        ? route('dashboard')
        : ($hasLogin ? route('login') : url('/'));
    $primaryLabel = $isAuthenticated && $hasDashboard ? 'Open Dashboard' : ($hasLogin ? 'Open Login' : 'Go Home');
    $homeUrl = url('/');
    $previousUrl = url()->previous();
    $canGoBack = filled($previousUrl) && $previousUrl !== url()->current();
    $requestMethod = request()->method();
    $hostLabel = request()->getHost();
    $contextLabel = $isAuthenticated ? 'Secure workspace' : 'Guest access';
    $timestampLabel = now()->format('d M Y, h:i A');
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} | {{ $appName }}</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.webp') }}">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        @keyframes floatOrb {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes pulseRing {
            0% {
                transform: translate(-50%, -50%) scale(0.92);
                opacity: 0.38;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.08);
                opacity: 0.08;
            }
        }

        @keyframes riseIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        :root {
            --bg-main: #eef4fb;
            --bg-soft: #f7faff;
            --card: #ffffff;
            --line: #d5e0ef;
            --line-soft: #e7edf7;
            --text: #13243d;
            --muted: #60718d;
            --primary: #145fd1;
            --primary-soft: #ecf4ff;
            --danger: #c53a3a;
            --warning: #c27b11;
            --shadow: 0 18px 36px rgba(10, 28, 56, 0.12);
        }

        html[data-theme="dark"] {
            --bg-main: #0c1426;
            --bg-soft: #111d34;
            --card: #152642;
            --line: #2c4268;
            --line-soft: #243955;
            --text: #e9f1ff;
            --muted: #aabbd7;
            --primary: #78afff;
            --primary-soft: #203c63;
            --danger: #f17070;
            --warning: #f0bf66;
            --shadow: 0 24px 42px rgba(0, 0, 0, 0.34);
        }

        html, body {
            min-height: 100%;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 10% 4%, rgba(84, 142, 238, 0.14) 0%, rgba(84, 142, 238, 0) 42%),
                radial-gradient(circle at 88% 0%, rgba(57, 187, 174, 0.09) 0%, rgba(57, 187, 174, 0) 36%),
                linear-gradient(165deg, var(--bg-soft) 0%, var(--bg-main) 52%, #edf3fc 100%);
            overflow-x: hidden;
        }

        html[data-theme="dark"] body {
            background:
                radial-gradient(circle at 12% 0%, rgba(80, 121, 212, 0.2) 0%, rgba(80, 121, 212, 0) 40%),
                radial-gradient(circle at 88% 0%, rgba(35, 163, 150, 0.14) 0%, rgba(35, 163, 150, 0) 34%),
                linear-gradient(170deg, var(--bg-soft) 0%, var(--bg-main) 62%, #0f1a2f 100%);
        }

        .error-shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 22px 0 30px;
            position: relative;
            animation: riseIn 360ms ease;
        }

        .error-shell::before,
        .error-shell::after {
            content: "";
            position: fixed;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(18px);
            opacity: 0.45;
            z-index: 0;
        }

        .error-shell::before {
            top: -80px;
            left: -90px;
            background: radial-gradient(circle, rgba(20, 95, 209, 0.26), rgba(20, 95, 209, 0));
        }

        .error-shell::after {
            right: -120px;
            bottom: -120px;
            background: radial-gradient(circle, rgba(57, 187, 174, 0.2), rgba(57, 187, 174, 0));
        }

        .error-topbar,
        .error-panel,
        .error-footer {
            position: relative;
            z-index: 1;
        }

        .error-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 10px 0 18px;
        }

        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: inherit;
            min-width: 0;
        }

        .brand-mark-shell {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 22px;
            background:
                radial-gradient(circle at left top, rgba(255, 194, 96, 0.2), rgba(255, 194, 96, 0) 30%),
                linear-gradient(180deg, #ffffff, #fbfdff);
            border: 1px solid rgba(210, 223, 242, 0.92);
            padding: 14px 18px;
            box-shadow:
                0 16px 28px rgba(15, 45, 89, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.98);
            min-height: 86px;
            min-width: 138px;
        }

        .brand-mark {
            display: block;
            width: 100%;
            height: auto;
            max-width: 170px;
            object-fit: contain;
            filter: drop-shadow(0 10px 18px rgba(20, 51, 93, 0.08));
        }

        .brand-copy {
            display: grid;
            gap: 3px;
        }

        .brand-copy span {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .brand-copy strong {
            font-size: 24px;
            line-height: 1.15;
        }

        .brand-copy p {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: var(--card);
            color: var(--text);
            display: grid;
            place-content: center;
            cursor: pointer;
            transition: transform 160ms ease, border-color 160ms ease, background-color 160ms ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 18px rgba(16, 39, 76, 0.08);
        }

        .theme-toggle:hover {
            transform: translateY(-1px);
            border-color: #aac7f1;
            background: var(--primary-soft);
        }

        .theme-toggle .sun-icon,
        .theme-toggle .moon-icon {
            position: absolute;
            left: 50%;
            top: 50%;
            transition: transform 260ms ease, opacity 220ms ease;
        }

        .theme-toggle .sun-icon {
            opacity: 1;
            transform: translate(-50%, -50%) translateY(0) rotate(0deg) scale(1);
        }

        .theme-toggle .moon-icon {
            opacity: 0;
            transform: translate(-50%, -50%) translateY(10px) rotate(-45deg) scale(0.7);
        }

        html[data-theme="dark"] .theme-toggle .sun-icon {
            opacity: 0;
            transform: translate(-50%, -50%) translateY(-10px) rotate(40deg) scale(0.7);
        }

        html[data-theme="dark"] .theme-toggle .moon-icon {
            opacity: 1;
            transform: translate(-50%, -50%) translateY(0) rotate(0deg) scale(1);
        }

        .error-panel {
            border: 1px solid var(--line);
            border-radius: 28px;
            background:
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #ffffff 6%), color-mix(in srgb, var(--card) 98%, var(--primary-soft) 2%));
            box-shadow: var(--shadow);
            overflow: hidden;
            backdrop-filter: blur(10px);
            position: relative;
            isolation: isolate;
        }

        .error-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: 0.38;
            pointer-events: none;
            z-index: -1;
        }

        .error-panel::after {
            content: "";
            position: absolute;
            inset: auto -120px -150px auto;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(20, 95, 209, 0.14), rgba(20, 95, 209, 0));
            pointer-events: none;
            z-index: -1;
        }

        .tone-alert .error-panel::after {
            background: radial-gradient(circle, rgba(197, 58, 58, 0.16), rgba(197, 58, 58, 0));
        }

        .tone-warm .error-panel::after {
            background: radial-gradient(circle, rgba(194, 123, 17, 0.16), rgba(194, 123, 17, 0));
        }

        .error-panel-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
        }

        .error-copy-wrap {
            padding: 38px 40px 34px;
            display: grid;
            gap: 22px;
            position: relative;
        }

        .error-copy-wrap::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.45), rgba(255, 255, 255, 0) 34%),
                linear-gradient(120deg, rgba(20, 95, 209, 0.03), rgba(20, 95, 209, 0) 48%);
            pointer-events: none;
        }

        .error-copy {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 18px;
        }

        .hero-intro {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 250px;
            gap: 18px;
            align-items: center;
        }

        .hero-text {
            display: grid;
            gap: 16px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 6px 12px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .tone-alert .eyebrow {
            background: rgba(197, 58, 58, 0.12);
            color: var(--danger);
        }

        .tone-warm .eyebrow {
            background: rgba(194, 123, 17, 0.12);
            color: var(--warning);
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-pill,
        .path-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .status-pill {
            background: linear-gradient(135deg, #125fd1, #2a79da);
            color: #fff;
            box-shadow: 0 12px 24px rgba(18, 95, 209, 0.22);
        }

        .tone-alert .status-pill {
            background: linear-gradient(135deg, #b43535, #e06767);
            box-shadow: 0 12px 24px rgba(180, 53, 53, 0.24);
        }

        .tone-warm .status-pill {
            background: linear-gradient(135deg, #b87010, #e0a23a);
            box-shadow: 0 12px 24px rgba(184, 112, 16, 0.24);
        }

        .path-pill {
            background: color-mix(in srgb, var(--card) 86%, var(--primary-soft) 14%);
            color: var(--muted);
            border: 1px solid var(--line-soft);
        }

        .error-copy h1 {
            margin: 0;
            font-size: clamp(34px, 4vw, 54px);
            line-height: 1.02;
            max-width: 12ch;
        }

        .lead {
            margin: 0;
            max-width: 60ch;
            font-size: 16px;
            line-height: 1.7;
            color: var(--muted);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .detail-card {
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 14px 15px;
            background:
                linear-gradient(180deg, color-mix(in srgb, var(--card) 96%, #ffffff 4%), color-mix(in srgb, var(--card) 98%, var(--primary-soft) 2%));
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.42),
                0 10px 18px rgba(16, 39, 76, 0.05);
            display: grid;
            gap: 6px;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .detail-value {
            font-size: 14px;
            line-height: 1.45;
            word-break: break-word;
        }

        .hero-visual {
            min-height: 246px;
            position: relative;
            display: grid;
            place-items: center;
            animation: floatOrb 8s ease-in-out infinite;
        }

        .signal-ring {
            position: absolute;
            left: 50%;
            top: 50%;
            border-radius: 50%;
            border: 1px solid rgba(20, 95, 209, 0.2);
            pointer-events: none;
        }

        .signal-ring-a {
            width: 202px;
            height: 202px;
            animation: pulseRing 3.6s ease-out infinite alternate;
        }

        .signal-ring-b {
            width: 158px;
            height: 158px;
            animation: pulseRing 3.6s ease-out 0.6s infinite alternate;
        }

        .tone-alert .signal-ring {
            border-color: rgba(197, 58, 58, 0.24);
        }

        .tone-warm .signal-ring {
            border-color: rgba(194, 123, 17, 0.24);
        }

        .signal-core {
            width: 158px;
            height: 158px;
            border-radius: 36px;
            padding: 18px;
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.44), rgba(255, 255, 255, 0) 48%),
                linear-gradient(150deg, rgba(20, 95, 209, 0.14), rgba(42, 121, 218, 0.1), color-mix(in srgb, var(--card) 94%, var(--primary-soft) 6%));
            border: 1px solid rgba(20, 95, 209, 0.18);
            box-shadow:
                0 24px 34px rgba(18, 95, 209, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            display: grid;
            align-content: center;
            justify-items: center;
            text-align: center;
            gap: 4px;
        }

        .tone-alert .signal-core {
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0) 48%),
                linear-gradient(150deg, rgba(197, 58, 58, 0.14), rgba(224, 103, 103, 0.1), color-mix(in srgb, var(--card) 96%, rgba(197, 58, 58, 0.08) 4%));
            border-color: rgba(197, 58, 58, 0.2);
            box-shadow:
                0 24px 34px rgba(180, 53, 53, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .tone-warm .signal-core {
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0) 48%),
                linear-gradient(150deg, rgba(194, 123, 17, 0.16), rgba(224, 162, 58, 0.1), color-mix(in srgb, var(--card) 96%, rgba(194, 123, 17, 0.08) 4%));
            border-color: rgba(194, 123, 17, 0.22);
            box-shadow:
                0 24px 34px rgba(184, 112, 16, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .signal-core small,
        .signal-core span {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .signal-core strong {
            font-size: clamp(48px, 5vw, 66px);
            line-height: 0.92;
            color: var(--primary);
        }

        .tone-alert .signal-core strong {
            color: var(--danger);
        }

        .tone-warm .signal-core strong {
            color: var(--warning);
        }

        .signal-card {
            position: absolute;
            border: 1px solid var(--line-soft);
            border-radius: 16px;
            padding: 12px 14px;
            min-width: 124px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            box-shadow: 0 14px 24px rgba(16, 39, 76, 0.08);
            display: grid;
            gap: 5px;
        }

        html[data-theme="dark"] .signal-card {
            background: rgba(17, 30, 52, 0.76);
        }

        .signal-card span {
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .signal-card strong {
            font-size: 14px;
            line-height: 1.35;
        }

        .signal-card-top {
            top: 24px;
            right: 10px;
        }

        .signal-card-bottom {
            left: 4px;
            bottom: 24px;
        }

        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 46px;
            border-radius: 14px;
            padding: 11px 18px;
            border: 1px solid var(--line);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease, background-color 160ms ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #125fd1, #2a79da);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 14px 26px rgba(18, 95, 209, 0.2);
        }

        .tone-alert .btn-primary {
            background: linear-gradient(135deg, #b43535, #e06767);
            box-shadow: 0 14px 26px rgba(180, 53, 53, 0.2);
        }

        .tone-warm .btn-primary {
            background: linear-gradient(135deg, #b87010, #e0a23a);
            box-shadow: 0 14px 26px rgba(184, 112, 16, 0.2);
        }

        .btn-secondary {
            background: var(--card);
            color: var(--text);
            box-shadow: 0 10px 20px rgba(17, 37, 72, 0.07);
        }

        .btn-secondary:hover,
        .btn-ghost:hover {
            border-color: #bfd3ef;
            background: var(--primary-soft);
        }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
        }

        .error-side {
            border-left: 1px solid var(--line-soft);
            background:
                radial-gradient(circle at top right, rgba(20, 95, 209, 0.12), rgba(20, 95, 209, 0) 34%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 92%, #ffffff 8%), color-mix(in srgb, var(--card) 97%, var(--primary-soft) 3%));
            padding: 30px 28px;
            display: grid;
            gap: 18px;
            align-content: start;
        }

        .tone-alert .error-side {
            background:
                radial-gradient(circle at top right, rgba(197, 58, 58, 0.12), rgba(197, 58, 58, 0) 34%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #ffffff 6%), color-mix(in srgb, var(--card) 98%, rgba(197, 58, 58, 0.08) 2%));
        }

        .tone-warm .error-side {
            background:
                radial-gradient(circle at top right, rgba(194, 123, 17, 0.12), rgba(194, 123, 17, 0) 34%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #ffffff 6%), color-mix(in srgb, var(--card) 98%, rgba(194, 123, 17, 0.08) 2%));
        }

        .code-card,
        .help-card,
        .support-card {
            border: 1px solid var(--line-soft);
            border-radius: 20px;
            background: color-mix(in srgb, var(--card) 90%, #ffffff 10%);
            padding: 20px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35);
        }

        .code-card {
            display: grid;
            gap: 10px;
            text-align: center;
        }

        .code-label {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .code-card strong {
            font-size: clamp(52px, 8vw, 92px);
            line-height: 0.95;
            color: var(--primary);
        }

        .tone-alert .code-card strong {
            color: var(--danger);
        }

        .tone-warm .code-card strong {
            color: var(--warning);
        }

        .code-card p,
        .support-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 14px;
        }

        .help-card h2,
        .support-card h2 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .help-card ul {
            margin: 0;
            padding-left: 18px;
            display: grid;
            gap: 10px;
            color: var(--muted);
            line-height: 1.6;
            font-size: 14px;
        }

        .error-footer {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            padding: 18px 4px 0;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 980px) {
            .error-panel-grid {
                grid-template-columns: 1fr;
            }

            .error-side {
                border-left: 0;
                border-top: 1px solid var(--line-soft);
            }

            .hero-intro {
                grid-template-columns: 1fr;
            }

            .hero-visual {
                order: -1;
                min-height: 214px;
            }
        }

        @media (max-width: 720px) {
            .error-shell {
                width: min(100%, calc(100% - 24px));
                padding-top: 14px;
            }

            .error-topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .brand-link {
                flex-direction: column;
                align-items: stretch;
            }

            .brand-copy strong {
                font-size: 20px;
            }

            .topbar-actions {
                justify-content: space-between;
            }

            .error-copy-wrap,
            .error-side {
                padding: 24px 20px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .action-row {
                display: grid;
            }

            .btn {
                width: 100%;
            }

            .signal-card-top,
            .signal-card-bottom {
                position: static;
            }

            .hero-visual {
                gap: 10px;
                min-height: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="error-shell tone-{{ $tone }}">
        <header class="error-topbar">
            <a href="{{ $homeUrl }}" class="brand-link">
                <div class="brand-mark-shell">
                    <img src="{{ asset('images/logo.webp') }}" alt="{{ $appName }}" class="brand-mark">
                </div>
                <div class="brand-copy">
                    <span>{{ $appName }}</span>
                    <strong>@yield('headline', 'System notice')</strong>
                    <p>@yield('subheadline', 'A styled recovery page built to match the rest of the dashboard experience.')</p>
                </div>
            </a>
            <div class="topbar-actions">
                @if ($canGoBack)
                    <button type="button" class="btn btn-ghost" onclick="window.history.back()">Go Back</button>
                @endif
                <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle theme" title="Toggle theme">
                    <svg class="sun-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <svg class="moon-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path>
                    </svg>
                </button>
            </div>
        </header>

        <main class="error-panel">
            <div class="error-panel-grid">
                <section class="error-copy-wrap">
                    <div class="error-copy">
                        <div class="hero-intro">
                            <div class="hero-text">
                                <span class="eyebrow">@yield('eyebrow', 'System status')</span>
                                <div class="status-row">
                                    <span class="status-pill">{{ $status }}</span>
                                    <span class="path-pill">{{ $pathLabel }}</span>
                                </div>
                                <h1>@yield('headline', 'We could not complete this request.')</h1>
                                <p class="lead">@yield('message', 'Please try again, or use the navigation links below to return to a stable area of the platform.')</p>
                            </div>
                            <div class="hero-visual" aria-hidden="true">
                                <div class="signal-ring signal-ring-a"></div>
                                <div class="signal-ring signal-ring-b"></div>
                                <div class="signal-core">
                                    <small>Status</small>
                                    <strong>{{ $status }}</strong>
                                    <span>{{ $requestMethod }}</span>
                                </div>
                                <div class="signal-card signal-card-top">
                                    <span>Access</span>
                                    <strong>{{ $contextLabel }}</strong>
                                </div>
                                <div class="signal-card signal-card-bottom">
                                    <span>Host</span>
                                    <strong>{{ $hostLabel }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="detail-grid">
                            <div class="detail-card">
                                <span class="detail-label">Request Method</span>
                                <strong class="detail-value">{{ $requestMethod }}</strong>
                            </div>
                            <div class="detail-card">
                                <span class="detail-label">Workspace</span>
                                <strong class="detail-value">{{ $contextLabel }}</strong>
                            </div>
                            <div class="detail-card">
                                <span class="detail-label">Time</span>
                                <strong class="detail-value">{{ $timestampLabel }}</strong>
                            </div>
                        </div>
                        <div class="action-row">
                            <a href="{{ $primaryUrl }}" class="btn btn-primary">{{ $primaryLabel }}</a>
                            <a href="{{ $homeUrl }}" class="btn btn-secondary">Go Home</a>
                            @yield('extra_actions')
                        </div>
                    </div>
                </section>

                <aside class="error-side">
                    <div class="code-card">
                        <span class="code-label">Status Code</span>
                        <strong>{{ $status }}</strong>
                        <p>@yield('aside_message', 'This page follows the same dashboard theme so your recovery path stays familiar and clear.')</p>
                    </div>

                    <div class="help-card">
                        <h2>Next Steps</h2>
                        <ul>
                            @hasSection('tips')
                                @yield('tips')
                            @else
                                <li>Refresh the page and try the action again once.</li>
                                <li>Return to the dashboard or home page and reopen the feature from the main menu.</li>
                                <li>If the issue keeps happening, share this status code with your administrator.</li>
                            @endif
                        </ul>
                    </div>

                    <div class="support-card">
                        <h2>Support Note</h2>
                        <p>@yield('support_note', 'If this problem repeats, note the page path and the time it happened so the issue can be traced quickly.')</p>
                    </div>
                </aside>
            </div>
        </main>

        <footer class="error-footer">
            <span>{{ $appName }}</span>
            <span>{{ now()->format('Y') }} | @yield('footer_note', 'Custom error page')</span>
        </footer>
    </div>

    <script src="{{ asset('js/theme.js') }}" defer></script>
</body>
</html>
