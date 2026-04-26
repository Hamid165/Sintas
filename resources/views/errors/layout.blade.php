<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') — CareHub</title>
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: blobFloat 8s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 { width: 500px; height: 500px; top: -100px; left: -100px; }
        .blob-2 { width: 400px; height: 400px; bottom: -80px; right: -80px; animation-delay: -4s; }
        .blob-3 { width: 300px; height: 300px; top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: -2s; }

        @keyframes blobFloat {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(20px, -20px) scale(1.05); }
        }
        .blob-3 { animation: blobFloat3 10s ease-in-out infinite alternate; }
        @keyframes blobFloat3 {
            from { transform: translate(-50%, -50%) scale(1); }
            to   { transform: translate(calc(-50% + 15px), calc(-50% - 15px)) scale(1.08); }
        }

        /* Card */
        .error-card {
            position: relative;
            z-index: 10;
            background: white;
            border-radius: 2.5rem;
            padding: 3rem 3.5rem;
            max-width: 520px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px -10px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04);
            animation: cardIn .5s cubic-bezier(.34,1.56,.64,1) forwards;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Big error code */
        .error-code {
            font-size: clamp(5rem, 18vw, 9rem);
            font-weight: 900;
            letter-spacing: -0.05em;
            line-height: 1;
            background: @yield('code-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)');
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
            animation: codeFloat 4s ease-in-out infinite alternate;
        }
        @keyframes codeFloat {
            from { transform: translateY(0); }
            to   { transform: translateY(-8px); }
        }

        /* Icon badge */
        .icon-badge {
            width: 72px; height: 72px;
            border-radius: 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            background: @yield('badge-bg', 'linear-gradient(135deg, #EFF6FF, #EEF2FF)');
        }

        /* Buttons */
        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .85rem 2rem;
            border-radius: 1rem;
            font-weight: 800;
            font-size: .75rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all .2s;
            background: @yield('btn-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)');
            color: white;
            box-shadow: 0 8px 24px -4px rgba(99,102,241,0.4);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -4px rgba(99,102,241,0.5); }
        .btn-primary:active { transform: scale(0.97); }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .85rem 2rem;
            border-radius: 1rem;
            font-weight: 800;
            font-size: .75rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all .2s;
            border: 2px solid #E5E7EB;
            color: #6B7280;
            background: white;
        }
        .btn-secondary:hover { border-color: #9CA3AF; color: #374151; transform: translateY(-2px); }
    </style>
</head>
<body>

    {{-- Background Blobs --}}
    <div class="blob blob-1" style="background: @yield('blob1-color', '#3B82F6');"></div>
    <div class="blob blob-2" style="background: @yield('blob2-color', '#6366F1');"></div>
    <div class="blob blob-3" style="background: @yield('blob3-color', '#8B5CF6');"></div>

    <div class="error-card">

        {{-- Icon Badge --}}
        <div class="icon-badge">
            @yield('icon')
        </div>

        {{-- Error Code --}}
        <div class="error-code">@yield('code')</div>

        {{-- Title & Description --}}
        <h1 style="font-size:1.35rem; font-weight:800; color:#0F172A; margin-bottom:.5rem; letter-spacing:-.02em;">
            @yield('heading')
        </h1>
        <p style="color:#94A3B8; font-size:.875rem; font-weight:600; line-height:1.6; margin-bottom:2rem;">
            @yield('description')
        </p>

        {{-- CTA Buttons --}}
        <div style="display:flex; gap:.75rem; justify-content:center; flex-wrap:wrap;">
            <a href="{{ url('/admin/dashboard') }}" class="btn-primary">
                <i data-lucide="home" style="width:16px;height:16px;"></i>
                Ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-secondary">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
                Kembali
            </a>
        </div>

        {{-- Branding --}}
        <div style="margin-top:2.5rem; padding-top:1.5rem; border-top:1px solid #F1F5F9;">
            <p style="font-size:.65rem; font-weight:800; color:#CBD5E1; letter-spacing:.15em; text-transform:uppercase;">
                CareHub — Sistem Manajemen Panti Asuhan
            </p>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
