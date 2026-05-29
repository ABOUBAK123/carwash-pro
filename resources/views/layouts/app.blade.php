<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CarWash Pro')</title>
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0d0f1a;
            --surface:   #111322;
            --border:    #1e2235;
            --border2:   #161829;
            --text:      #e2e8f0;
            --muted:     #64748b;
            --dim:       #475569;
            --brand:     #6366f1;
            --brand-15:  rgba(99,102,241,.15);
            --brand-20:  rgba(99,102,241,.20);
            --success:   #10b981;
            --danger:    #f43f5e;
            --warning:   #f59e0b;
        }

        /* Override Tailwind preflight where needed */
        .hidden { display: none !important; }

        html, body { height: 100% !important; }
        body {
            font-family: 'Inter', sans-serif !important;
            background: var(--bg) !important;
            color: var(--text) !important;
            display: flex !important;
            height: 100vh !important;
            overflow: hidden !important;
            margin: 0 !important;
        }

        /* ── SIDEBAR ─────────────────── */
        .sidebar {
            width: 256px !important;
            min-width: 256px !important;
            height: 100vh !important;
            background: var(--surface) !important;
            border-right: 1px solid var(--border) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 18px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .logo-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 16px rgba(99,102,241,.35);
        }
        .logo-icon i { color: #fff; font-size: 15px; }
        .logo-name { font-weight: 800; font-size: 15px; color: #fff; letter-spacing: -.02em; }
        .logo-sub  { font-size: 10px; color: var(--muted); font-weight: 500; margin-top: 1px; }

        .nav-body { flex: 1; min-height: 0; padding: 12px 10px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
        .nav-section {
            font-size: 10px; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: var(--dim);
            padding: 10px 8px 4px; margin-top: 4px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #94a3b8;
            text-decoration: none; transition: background .15s, color .15s;
            position: relative;
        }
        .nav-link:hover { background: rgba(255,255,255,.06); color: #cbd5e1; }
        .nav-link.active {
            background: var(--brand-15);
            color: #a5b4fc;
        }
        .nav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 3px; border-radius: 0 3px 3px 0;
            background: var(--brand);
        }
        .nav-icon { width: 16px; text-align: center; font-size: 13px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 10px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }
        .user-row {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 8px; border-radius: 10px;
            transition: background .15s;
        }
        .user-row:hover { background: rgba(255,255,255,.05); }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-name  { font-size: 13px; font-weight: 600; color: #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role  { font-size: 11px; color: var(--muted); text-transform: capitalize; }
        .logout-btn {
            margin-left: auto; width: 28px; height: 28px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            background: transparent; border: none; cursor: pointer;
            color: var(--muted); transition: all .15s; flex-shrink: 0;
        }
        .logout-btn:hover { background: rgba(244,63,94,.15); color: #fb7185; }

        /* ── MAIN ────────────────────── */
        .main-wrap {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            min-width: 0 !important;
            height: 100vh !important;
            overflow: hidden !important;
        }
        .topbar {
            height: 52px !important;
            background: var(--surface) !important;
            border-bottom: 1px solid var(--border) !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 24px !important;
            gap: 16px !important;
            flex-shrink: 0 !important;
        }
        .topbar-left  { flex: 1; font-size: 13px; color: var(--muted); display: flex; align-items: center; gap: 6px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .online-pill {
            display: flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 20px;
            background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2);
            font-size: 11px; font-weight: 600; color: #34d399;
        }
        .online-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #34d399;
            animation: blink 2s infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
        .topbar-date { font-size: 12px; color: var(--dim); }

        .page-content { flex: 1; overflow-y: auto; padding: 32px; }

        /* ── ALERTS ──────────────────── */
        .alert { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2); color: #34d399; }
        .alert-error   { background: rgba(244,63,94,.1);  border: 1px solid rgba(244,63,94,.2);  color: #fb7185; }

        /* ── PAGE HEADER ─────────────── */
        .page-header    { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 16px; }
        .page-title     { font-size: 20px; font-weight: 700; color: #f1f5f9; letter-spacing: -.02em; }
        .page-sub,
        .page-subtitle  { font-size: 13px; color: var(--muted); margin-top: 3px; }

        /* ── CARDS ───────────────────── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 18px; transition: border-color .2s; }
        .stat-card:hover { border-color: #2d3155; }

        /* ── TABLE ───────────────────── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            padding: 11px 16px; font-size: 11px; font-weight: 600;
            letter-spacing: .05em; text-transform: uppercase; color: var(--dim);
            text-align: left; border-bottom: 1px solid var(--border);
        }
        .data-table tbody tr { border-bottom: 1px solid var(--border2); transition: background .12s; }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: rgba(255,255,255,.025); }
        .data-table tbody td { padding: 13px 16px; font-size: 13px; color: #cbd5e1; vertical-align: middle; }
        .data-table tfoot tr { border-top: 1px solid var(--border); background: var(--bg); }
        .data-table tfoot td { padding: 12px 16px; }

        /* ── BUTTONS ─────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: none; transition: all .15s; text-decoration: none;
            font-family: inherit; white-space: nowrap;
        }
        .btn-primary  { background: var(--brand); color: #fff; }
        .btn-primary:hover  { background: #5457e0; box-shadow: 0 4px 18px rgba(99,102,241,.35); }
        .btn-outline  { background: transparent; color: #94a3b8; border: 1px solid var(--border); }
        .btn-outline:hover  { border-color: #4b5563; color: #e2e8f0; background: rgba(255,255,255,.04); }
        .btn-success  { background: rgba(16,185,129,.15);  color: #34d399; border: 1px solid rgba(16,185,129,.25); }
        .btn-success:hover  { background: rgba(16,185,129,.25); }
        .btn-danger   { background: rgba(244,63,94,.15);   color: #fb7185; border: 1px solid rgba(244,63,94,.25); }
        .btn-danger:hover   { background: rgba(244,63,94,.25); }
        .btn-warning  { background: rgba(245,158,11,.15);  color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
        .btn-warning:hover  { background: rgba(245,158,11,.25); }
        .btn-sm   { padding: 5px 10px; font-size: 12px; border-radius: 6px; }
        .btn-icon { padding: 6px 8px; }
        .btn-full { width: 100%; justify-content: center; }

        /* ── BADGES ──────────────────── */
        .badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-green  { background: rgba(16,185,129,.12);  color: #34d399; border: 1px solid rgba(16,185,129,.2); }
        .badge-red    { background: rgba(244,63,94,.12);   color: #fb7185; border: 1px solid rgba(244,63,94,.2); }
        .badge-blue   { background: rgba(99,102,241,.12);  color: #a5b4fc; border: 1px solid rgba(99,102,241,.2); }
        .badge-yellow { background: rgba(245,158,11,.12);  color: #fbbf24; border: 1px solid rgba(245,158,11,.2); }
        .badge-purple { background: rgba(168,85,247,.12);  color: #c084fc; border: 1px solid rgba(168,85,247,.2); }
        .badge-gray   { background: rgba(100,116,139,.12); color: #94a3b8; border: 1px solid rgba(100,116,139,.2); }

        /* ── FORMS ───────────────────── */
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-label { font-size: 11px; font-weight: 700; color: var(--muted); letter-spacing: .04em; text-transform: uppercase; }
        .form-input {
            background: var(--bg); border: 1px solid var(--border); border-radius: 8px;
            color: var(--text); padding: 9px 12px; font-size: 13px; font-family: inherit;
            transition: border-color .2s, box-shadow .2s; width: 100%;
        }
        .form-input:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .form-input::placeholder { color: var(--dim); }
        .form-input option { background: var(--surface); }
        textarea.form-input { resize: vertical; min-height: 72px; }

        /* ── MODALS ──────────────────── */
        .modal-backdrop {
            position: fixed; inset: 0; z-index: 100;
            background: rgba(0,0,0,.7);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .modal-box {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; width: 100%; max-height: 90vh; overflow-y: auto;
            box-shadow: 0 24px 60px rgba(0,0,0,.5);
        }
        .modal-header { padding: 22px 22px 0; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        .modal-body   { padding: 18px 22px 22px; }
        .modal-title  { font-size: 16px; font-weight: 700; color: #fff; }
        .modal-sub    { font-size: 12px; color: var(--muted); margin-top: 3px; }

        /* ── GRIDS ───────────────────── */
        .grid-2  { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .grid-3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .grid-4  { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }
        .grid-6  { display: grid; grid-template-columns: repeat(6,1fr); gap: 14px; }
        .col-2   { grid-column: span 2; }

        /* ── MISC ────────────────────── */
        .divider { border: none; border-top: 1px solid var(--border); margin: 6px 0; }
        .text-mono { font-family: 'Courier New', monospace; }
        .pill-filter { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 18px; }
        .pill {
            padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
            cursor: pointer; border: 1px solid var(--border); color: var(--muted);
            background: transparent; transition: all .15s; font-family: inherit;
        }
        .pill.active, .pill:hover { border-color: var(--brand); color: #a5b4fc; background: var(--brand-15); }

        /* ── SCROLLBAR ───────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #2d3155; }

        /* ── ANIMATION ───────────────── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp .25s ease forwards; }

        /* ── RESPONSIVE ──────────────── */
        @media (max-width: 768px) {
            .sidebar { width: 220px; min-width: 220px; }
            .page-content { padding: 20px; }
            .grid-4 { grid-template-columns: 1fr 1fr; }
            .grid-6 { grid-template-columns: 1fr 1fr; }
            .grid-3 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ═══ SIDEBAR ════════════════════════════════════════════ -->
<aside class="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.svg') }}" alt="CarWash Pro" style="width:44px;height:26px;flex-shrink:0;border-radius:4px;">
        <div>
            <div class="logo-name">CarWash Pro</div>
            <div class="logo-sub">Gestion Centre Lavage</div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="nav-body">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span> Tableau de bord
        </a>

        @if(auth()->user()->isAdmin())
            <div class="nav-section">Administration</div>
            <a href="{{ route('admin.carwashes') }}" class="nav-link {{ request()->routeIs('admin.carwashes*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-building"></i></span> Centres de lavage
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span> Utilisateurs
            </a>
            <a href="{{ route('admin.registrations') }}" class="nav-link {{ request()->routeIs('admin.registrations*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-file-circle-plus"></i></span> Inscriptions
            </a>
            <a href="{{ route('admin.commissionnaires') }}" class="nav-link {{ request()->routeIs('admin.commissionnaires*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-handshake"></i></span> Commissionnaires
                @php $pendingCom = \App\Models\Commission::where('status','pending')->sum('commission_amount_xof'); @endphp
                @if($pendingCom > 0)
                <span class="badge badge-yellow" style="margin-left:auto;font-size:9px;padding:2px 6px;">!</span>
                @endif
            </a>
            <a href="{{ route('admin.currencies') }}" class="nav-link {{ request()->routeIs('admin.currencies*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-coins"></i></span> Devises
            </a>
            <div class="nav-section">Paramètres système</div>
            <a href="{{ route('admin.payment-settings') }}" class="nav-link {{ request()->routeIs('admin.payment-settings*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-credit-card"></i></span> Paiements
            </a>
            <a href="{{ route('admin.email-settings') }}" class="nav-link {{ request()->routeIs('admin.email-settings*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-envelope-open-text"></i></span> Email
            </a>
            <a href="{{ route('admin.terms-settings') }}" class="nav-link {{ request()->routeIs('admin.terms-settings*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-file-contract"></i></span> Conditions
            </a>
            <a href="{{ route('admin.plans') }}" class="nav-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-layer-group"></i></span> Plans & Abonnements
            </a>
        @endif

        @if(auth()->user()->isCommissionnaire())
            <div class="nav-section">Partenaire</div>
            <a href="{{ route('commissionnaire.dashboard') }}" class="nav-link {{ request()->routeIs('commissionnaire.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-gauge-high"></i></span> Tableau de bord
            </a>
            <a href="{{ route('commissionnaire.dashboard') }}#centers" class="nav-link">
                <span class="nav-icon"><i class="fas fa-building"></i></span> Mes centres
            </a>
            <a href="{{ route('commissionnaire.dashboard') }}#commissions" class="nav-link">
                <span class="nav-icon"><i class="fas fa-coins"></i></span> Commissions
            </a>
        @endif

        @if(!auth()->user()->isAdmin() && auth()->user()->carwash_id)
            <div class="nav-section">Opérations</div>
            <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-calendar-days"></i></span> Rendez-vous
            </a>
            <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-receipt"></i></span> Facturation
            </a>
            <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-group"></i></span> Clients
            </a>

            @if(auth()->user()->isManager())
                <div class="nav-section">Management</div>
                <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-id-badge"></i></span> Employés
                </a>
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-star"></i></span> Services
                </a>
                <a href="{{ route('salary.index') }}" class="nav-link {{ request()->routeIs('salary*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-wallet"></i></span> Salaires
                </a>
                <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-arrow-trend-down"></i></span> Dépenses
                </a>
                <a href="{{ route('equipment.index') }}" class="nav-link {{ request()->routeIs('equipment*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-toolbox"></i></span> Équipements
                </a>

                <div class="nav-section">Analyse</div>
                <a href="{{ route('profits.index') }}" class="nav-link {{ request()->routeIs('profits*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-chart-pie"></i></span> Profits
                </a>
                <a href="{{ route('performance.index') }}" class="nav-link {{ request()->routeIs('performance*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span> Performance
                </a>
                <a href="{{ route('loyalty.index') }}" class="nav-link {{ request()->routeIs('loyalty*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-gift"></i></span> Fidélité
                </a>

                <div class="nav-section">Paramètres</div>
                <a href="{{ route('sms.config') }}" class="nav-link {{ request()->routeIs('sms*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-message"></i></span> Config SMS
                </a>
            @endif
        @endif

        @if(!auth()->user()->isAdmin() && auth()->user()->carwash_id)
        <hr class="divider" style="margin:8px 0;">
        <a href="{{ route('subscription.index') }}" class="nav-link {{ request()->routeIs('subscription*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-gem"></i></span> Mon abonnement
            @php $cw = auth()->user()->carwash; @endphp
            @if($cw && $cw->isOnTrial())
                <span class="badge badge-yellow" style="margin-left:auto;font-size:9px;padding:2px 6px;">Essai</span>
            @elseif($cw && $cw->subscriptionExpired())
                <span class="badge badge-red" style="margin-left:auto;font-size:9px;padding:2px 6px;">Expiré</span>
            @endif
        </a>
        @endif
        <hr class="divider" style="margin:8px 0;">
        <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-circle-user"></i></span> Mon profil
        </a>
    </nav>

    <!-- User -->
    <div class="sidebar-footer">
        <div class="user-row">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->first_name,0,1)).strtoupper(substr(auth()->user()->last_name,0,1)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <div class="user-name">{{ auth()->user()->full_name }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Déconnexion">
                    <i class="fas fa-right-from-bracket" style="font-size:12px;"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ═══ MAIN ════════════════════════════════════════════════ -->
<div class="main-wrap">

    <!-- Top bar -->
    <header class="topbar">
        <div class="topbar-left">
            @if(auth()->user()->isAdmin())
                <i class="fas fa-shield-halved" style="color:#6366f1;font-size:12px;"></i>
                <span>Administration système</span>
            @elseif(auth()->user()->carwash)
                <i class="fas fa-location-dot" style="color:#6366f1;font-size:12px;"></i>
                <span>{{ auth()->user()->carwash->name }}</span>
            @endif
        </div>
        <div class="topbar-right">
            <span class="topbar-date">{{ now()->isoFormat('ddd D MMM YYYY') }}</span>
            <div style="width:1px;height:16px;background:var(--border);"></div>
            <div class="online-pill">
                <span class="online-dot"></span>
                En ligne
            </div>
        </div>
    </header>

    <!-- Subscription warning banner -->
    @if(!auth()->user()->isAdmin() && auth()->user()->carwash_id)
    @php $__cw = auth()->user()->carwash; @endphp
    @if($__cw && $__cw->isOnTrial() && $__cw->daysRemaining() <= 5)
    <div style="background:rgba(245,158,11,.1);border-bottom:1px solid rgba(245,158,11,.25);padding:8px 24px;font-size:12px;color:#fbbf24;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-triangle-exclamation"></i>
        Votre essai expire dans <strong>{{ $__cw->daysRemaining() }} jour(s)</strong>.
        <a href="{{ route('subscription.index') }}" style="color:#fbbf24;font-weight:700;text-decoration:underline;margin-left:4px;">Choisir un plan →</a>
    </div>
    @elseif($__cw && $__cw->subscriptionExpired())
    <div style="background:rgba(244,63,94,.1);border-bottom:1px solid rgba(244,63,94,.25);padding:8px 24px;font-size:12px;color:#fb7185;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-circle-xmark"></i>
        Votre abonnement a expiré.
        <a href="{{ route('subscription.index') }}" style="color:#fb7185;font-weight:700;text-decoration:underline;margin-left:4px;">Renouveler maintenant →</a>
    </div>
    @endif
    @endif

    <!-- Content -->
    <main class="page-content">

        @if(session('success'))
        <div class="alert alert-success fade-up">
            <i class="fas fa-circle-check" style="flex-shrink:0;margin-top:1px;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error fade-up">
            <i class="fas fa-circle-exclamation" style="flex-shrink:0;margin-top:1px;"></i>
            <ul style="list-style:none;">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="fade-up">
            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
