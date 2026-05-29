<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CarWash Pro')</title>
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0d0f1a; color: #e2e8f0; min-height: 100vh; }

        .auth-bg {
            background: #0d0f1a;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(99,102,241,.18) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(168,85,247,.1) 0%, transparent 60%);
        }

        .auth-card {
            background: #111322;
            border: 1px solid #1e2235;
            border-radius: 20px;
            box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(99,102,241,.05);
        }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 12px; font-weight: 600; color: #94a3b8; letter-spacing: .02em; }
        .form-input {
            background: #0d0f1a;
            border: 1px solid #1e2235;
            border-radius: 9px;
            color: #e2e8f0;
            padding: 11px 14px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
        }
        .form-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .form-input::placeholder { color: #3b4563; }
        .form-input option { background: #111322; }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all .2s;
            letter-spacing: .02em;
        }
        .btn-submit:hover { opacity: .92; box-shadow: 0 8px 25px rgba(99,102,241,.4); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .input-icon-wrap { position: relative; }
        .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #3b4563; font-size: 13px; pointer-events: none; }
        .input-icon-wrap .form-input { padding-left: 38px; }

        .divider-text { display: flex; align-items: center; gap: 12px; color: #334155; font-size: 12px; }
        .divider-text::before, .divider-text::after { content: ''; flex: 1; height: 1px; background: #1e2235; }

        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2); color: #34d399; border-radius: 9px; padding: 11px 14px; font-size: 13px; }
        .alert-error { background: rgba(244,63,94,.1); border: 1px solid rgba(244,63,94,.2); color: #fb7185; border-radius: 9px; padding: 11px 14px; font-size: 13px; }

        /* Grid bg decoration */
        .grid-bg {
            background-image: linear-gradient(rgba(99,102,241,.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(99,102,241,.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp .4s ease forwards; }
    </style>
</head>
<body class="auth-bg grid-bg flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-[420px] fade-up">

        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.svg') }}" alt="CarWash Pro" class="mx-auto mb-2" style="width:260px;height:auto;border-radius:12px;">
            <h1 class="text-2xl font-extrabold text-white tracking-tight">CarWash Pro</h1>
            <p class="text-slate-500 text-sm mt-1">Système de gestion centre de lavage</p>
        </div>

        <!-- Card -->
        <div class="auth-card p-8">
            @if(session('success'))
            <div class="alert-success mb-5">
                <i class="fas fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert-error mb-5">
                <i class="fas fa-circle-exclamation mr-2"></i>
                @foreach($errors->all() as $err)
                <span>{{ $err }}</span>
                @endforeach
            </div>
            @endif

            @yield('content')
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">
            CarWash Pro &copy; {{ date('Y') }} — Tous droits réservés
        </p>
    </div>
</body>
</html>
