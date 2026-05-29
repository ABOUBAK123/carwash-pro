<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs — CarWash Pro</title>
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.min.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #070b14;
            --surface: #0d1117;
            --border: #1a2035;
            --text: #e2e8f0;
            --muted: #64748b;
            --brand: #6366f1;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        /* NAV */
        .nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 40px; border-bottom: 1px solid var(--border);
            background: rgba(7,11,20,.9); backdrop-filter: blur(12px);
            position: sticky; top: 0; z-index: 10;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { height: 32px; width: auto; border-radius: 4px; }
        .nav-logo span { font-size: 17px; font-weight: 800; color: #fff; letter-spacing: -.02em; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-btn {
            padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; text-decoration: none; transition: all .15s;
        }
        .nav-btn-ghost { color: #94a3b8; }
        .nav-btn-ghost:hover { color: #e2e8f0; }
        .nav-btn-primary { background: var(--brand); color: #fff; }
        .nav-btn-primary:hover { background: #5457e0; }

        /* HERO */
        .hero {
            text-align: center; padding: 90px 40px 70px;
            background: radial-gradient(ellipse 80% 50% at 50% -10%, rgba(99,102,241,.18) 0%, transparent 70%);
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
            background: rgba(99,102,241,.1); border: 1px solid rgba(99,102,241,.25); color: #a5b4fc;
            margin-bottom: 24px;
        }
        .hero h1 {
            font-size: clamp(32px, 5vw, 52px); font-weight: 900; color: #fff;
            letter-spacing: -.03em; line-height: 1.1; margin-bottom: 18px;
        }
        .hero h1 span { color: #6366f1; }
        .hero p { font-size: 17px; color: var(--muted); max-width: 520px; margin: 0 auto 32px; line-height: 1.6; }

        /* TOGGLE */
        .billing-toggle {
            display: inline-flex; align-items: center; gap: 12px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 40px; padding: 5px 8px; margin-bottom: 60px;
        }
        .toggle-btn {
            padding: 7px 20px; border-radius: 30px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: none; font-family: inherit; transition: all .2s;
        }
        .toggle-btn.active { background: var(--brand); color: #fff; }
        .toggle-btn:not(.active) { background: transparent; color: var(--muted); }
        .save-badge {
            font-size: 11px; font-weight: 700; color: #34d399;
            background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2);
            padding: 2px 8px; border-radius: 10px;
        }

        /* CARDS */
        .plans-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 20px; max-width: 1160px; margin: 0 auto; padding: 0 40px 80px;
        }
        .plan-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 20px; padding: 28px; position: relative;
            transition: transform .2s, border-color .2s;
            display: flex; flex-direction: column;
        }
        .plan-card:hover { transform: translateY(-4px); }
        .plan-card.popular {
            border-color: #6366f1;
            background: linear-gradient(180deg, rgba(99,102,241,.07) 0%, var(--surface) 60%);
            box-shadow: 0 0 0 1px rgba(99,102,241,.3), 0 20px 60px rgba(99,102,241,.15);
        }
        .popular-badge {
            position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            color: #fff; font-size: 11px; font-weight: 700; letter-spacing: .05em;
            padding: 4px 16px; border-radius: 20px; white-space: nowrap;
        }
        .plan-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 16px; flex-shrink: 0;
        }
        .plan-name { font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .plan-desc { font-size: 13px; color: var(--muted); margin-bottom: 20px; line-height: 1.5; }

        .plan-price { margin-bottom: 6px; }
        .price-xof { font-size: 34px; font-weight: 900; color: #fff; letter-spacing: -.02em; }
        .price-xof span { font-size: 16px; font-weight: 500; color: var(--muted); }
        .price-eur { font-size: 13px; color: var(--muted); margin-bottom: 20px; }

        .plan-divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

        .features-list { list-style: none; display: flex; flex-direction: column; gap: 10px; flex: 1; }
        .feature-item { display: flex; align-items: center; gap: 10px; font-size: 13px; }
        .feature-item.off { color: #3d4d66; }
        .feature-check { width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 9px; flex-shrink: 0; }
        .check-on  { background: rgba(16,185,129,.15); color: #34d399; }
        .check-off { background: rgba(100,116,139,.08); color: #3d4d66; }

        .plan-cta {
            display: block; text-align: center; margin-top: 24px;
            padding: 11px; border-radius: 10px; font-size: 14px; font-weight: 700;
            cursor: pointer; text-decoration: none; transition: all .15s; font-family: inherit;
            border: none;
        }
        .cta-primary  { background: #6366f1; color: #fff; }
        .cta-primary:hover  { background: #5457e0; box-shadow: 0 6px 20px rgba(99,102,241,.4); }
        .cta-outline  { background: transparent; color: #94a3b8; border: 1px solid var(--border); }
        .cta-outline:hover  { border-color: #6366f1; color: #a5b4fc; }
        .cta-trial    { background: rgba(100,116,139,.1); color: #94a3b8; border: 1px solid var(--border); }
        .cta-business { background: linear-gradient(90deg, #f59e0b, #ef4444); color: #fff; }
        .cta-business:hover { opacity: .9; box-shadow: 0 6px 20px rgba(245,158,11,.35); }

        /* FAQ */
        .faq-section { max-width: 700px; margin: 0 auto; padding: 0 40px 80px; }
        .faq-title { text-align: center; font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 40px; }
        .faq-item { border-bottom: 1px solid var(--border); padding: 18px 0; }
        .faq-q { font-size: 14px; font-weight: 600; color: #e2e8f0; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .faq-a { font-size: 13px; color: var(--muted); margin-top: 10px; line-height: 1.6; display: none; }

        /* FOOTER */
        .footer { text-align: center; padding: 32px; border-top: 1px solid var(--border); font-size: 13px; color: var(--muted); }
        .footer a { color: #6366f1; text-decoration: none; }

        @media (max-width: 900px) {
            .plans-grid { grid-template-columns: 1fr 1fr; }
            .nav { padding: 16px 20px; }
        }
        @media (max-width: 560px) {
            .plans-grid { grid-template-columns: 1fr; padding: 0 20px 60px; }
            .hero { padding: 60px 20px 50px; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
    <a href="{{ route('login') }}" class="nav-logo">
        <img src="{{ asset('images/logo.svg') }}" alt="CarWash Pro">
        <span>CarWash Pro</span>
    </a>
    <div class="nav-links">
        @auth
        <a href="{{ route('dashboard') }}" class="nav-btn nav-btn-ghost">Tableau de bord</a>
        @else
        <a href="{{ route('login') }}" class="nav-btn nav-btn-ghost">Se connecter</a>
        <a href="{{ route('register') }}" class="nav-btn nav-btn-primary">Démarrer gratuitement</a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-badge">
        <i class="fas fa-bolt"></i> Simple, transparent, sans engagement
    </div>
    <h1>Des tarifs pensés pour<br>les <span>centres de lavage</span></h1>
    <p>Commencez gratuitement pendant 14 jours. Aucune carte bancaire requise. Passez au plan supérieur quand vous êtes prêt.</p>

    <div class="billing-toggle">
        <button class="toggle-btn active" id="btnMonthly" onclick="setBilling('monthly')">Mensuel</button>
        <button class="toggle-btn" id="btnYearly" onclick="setBilling('yearly')">
            Annuel <span class="save-badge">-20%</span>
        </button>
    </div>
</section>

<!-- PLANS -->
<div class="plans-grid">

    <!-- ESSAI -->
    <div class="plan-card">
        <div class="plan-icon" style="background:rgba(100,116,139,.12);">
            <i class="fas fa-seedling" style="color:#94a3b8;"></i>
        </div>
        <div class="plan-name">Essai gratuit</div>
        <div class="plan-desc">Découvrez toutes les fonctionnalités de base sans engagement.</div>
        <div class="plan-price">
            <div class="price-xof">0 <span>XOF</span></div>
        </div>
        <div class="price-eur">Gratuit pendant 14 jours</div>
        <hr class="plan-divider">
        <ul class="features-list">
            @foreach(\App\Models\SubscriptionPlan::get('trial')['features'] as $f)
            <li class="feature-item {{ $f['included'] ? '' : 'off' }}">
                <span class="feature-check {{ $f['included'] ? 'check-on' : 'check-off' }}">
                    <i class="fas fa-{{ $f['included'] ? 'check' : 'times' }}"></i>
                </span>
                {{ $f['label'] }}
            </li>
            @endforeach
        </ul>
        <a href="{{ route('register') }}" class="plan-cta cta-trial">Démarrer l'essai</a>
    </div>

    <!-- STARTER -->
    <div class="plan-card">
        <div class="plan-icon" style="background:rgba(59,130,246,.12);">
            <i class="fas fa-rocket" style="color:#60a5fa;"></i>
        </div>
        <div class="plan-name">Starter</div>
        <div class="plan-desc">Pour les centres qui démarrent et veulent professionnaliser leur gestion.</div>
        <div class="plan-price">
            <div class="price-xof monthly-price" data-monthly="9 900" data-yearly="7 920">9 900 <span>XOF / mois</span></div>
        </div>
        <div class="price-eur monthly-eur" data-monthly="≈ 14,99 €" data-yearly="≈ 11,99 €">≈ 14,99 € / mois</div>
        <hr class="plan-divider">
        <ul class="features-list">
            @foreach(\App\Models\SubscriptionPlan::get('starter')['features'] as $f)
            <li class="feature-item {{ $f['included'] ? '' : 'off' }}">
                <span class="feature-check {{ $f['included'] ? 'check-on' : 'check-off' }}">
                    <i class="fas fa-{{ $f['included'] ? 'check' : 'times' }}"></i>
                </span>
                {{ $f['label'] }}
            </li>
            @endforeach
        </ul>
        <a href="{{ route('register') }}" class="plan-cta cta-outline">Choisir Starter</a>
    </div>

    <!-- PRO -->
    <div class="plan-card popular">
        <div class="popular-badge">⭐ Populaire</div>
        <div class="plan-icon" style="background:rgba(99,102,241,.15);">
            <i class="fas fa-crown" style="color:#a5b4fc;"></i>
        </div>
        <div class="plan-name">Pro</div>
        <div class="plan-desc">La solution complète pour les centres qui veulent tout maîtriser.</div>
        <div class="plan-price">
            <div class="price-xof monthly-price" data-monthly="19 900" data-yearly="15 920">19 900 <span>XOF / mois</span></div>
        </div>
        <div class="price-eur monthly-eur" data-monthly="≈ 29,99 €" data-yearly="≈ 23,99 €">≈ 29,99 € / mois</div>
        <hr class="plan-divider">
        <ul class="features-list">
            @foreach(\App\Models\SubscriptionPlan::get('pro')['features'] as $f)
            <li class="feature-item {{ $f['included'] ? '' : 'off' }}">
                <span class="feature-check {{ $f['included'] ? 'check-on' : 'check-off' }}">
                    <i class="fas fa-{{ $f['included'] ? 'check' : 'times' }}"></i>
                </span>
                {{ $f['label'] }}
            </li>
            @endforeach
        </ul>
        <a href="{{ route('register') }}" class="plan-cta cta-primary">Choisir Pro</a>
    </div>

    <!-- BUSINESS -->
    <div class="plan-card">
        <div class="plan-icon" style="background:rgba(245,158,11,.12);">
            <i class="fas fa-building-columns" style="color:#fbbf24;"></i>
        </div>
        <div class="plan-name">Business</div>
        <div class="plan-desc">Multi-centres, équipes larges et support dédié pour les grandes structures.</div>
        <div class="plan-price">
            <div class="price-xof monthly-price" data-monthly="39 900" data-yearly="31 920">39 900 <span>XOF / mois</span></div>
        </div>
        <div class="price-eur monthly-eur" data-monthly="≈ 59,99 €" data-yearly="≈ 47,99 €">≈ 59,99 € / mois</div>
        <hr class="plan-divider">
        <ul class="features-list">
            @foreach(\App\Models\SubscriptionPlan::get('business')['features'] as $f)
            <li class="feature-item {{ $f['included'] ? '' : 'off' }}">
                <span class="feature-check {{ $f['included'] ? 'check-on' : 'check-off' }}">
                    <i class="fas fa-{{ $f['included'] ? 'check' : 'times' }}"></i>
                </span>
                {{ $f['label'] }}
            </li>
            @endforeach
        </ul>
        <a href="{{ route('register') }}" class="plan-cta cta-business">Choisir Business</a>
    </div>
</div>

<!-- FAQ -->
<section class="faq-section">
    <div class="faq-title">Questions fréquentes</div>

    @foreach([
        ['q' => 'Est-ce que je dois fournir une carte bancaire pour l\'essai ?',
         'a' => 'Non. L\'essai de 14 jours est entièrement gratuit et ne nécessite aucune carte bancaire. Vous pouvez tester toutes les fonctionnalités sans engagement.'],
        ['q' => 'Puis-je changer de plan à tout moment ?',
         'a' => 'Oui, vous pouvez passer à un plan supérieur ou inférieur à tout moment. Le changement est immédiat et la facturation est au prorata.'],
        ['q' => 'Comment sont calculés les prix en XOF ?',
         'a' => 'Les prix en XOF sont fixés selon le taux de change EUR/XOF (1 EUR ≈ 655,957 XOF). Ils sont révisés périodiquement pour rester compétitifs sur le marché africain.'],
        ['q' => 'Quels moyens de paiement acceptez-vous ?',
         'a' => 'Nous acceptons les cartes bancaires (Visa, Mastercard), Orange Money, MTN MoMo, Wave et Moov Money pour les paiements Mobile Money.'],
        ['q' => 'Les données sont-elles sécurisées ?',
         'a' => 'Oui. Toutes les données sont chiffrées (HTTPS/TLS), hébergées sur des serveurs sécurisés, avec des sauvegardes quotidiennes automatiques.'],
    ] as $faq)
    <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
            {{ $faq['q'] }}
            <i class="fas fa-chevron-down" style="font-size:12px;color:#64748b;flex-shrink:0;transition:transform .2s;"></i>
        </div>
        <div class="faq-a">{{ $faq['a'] }}</div>
    </div>
    @endforeach
</section>

<!-- FOOTER -->
<footer class="footer">
    CarWash Pro &copy; {{ date('Y') }} —
    <a href="{{ route('login') }}">Se connecter</a> ·
    <a href="{{ route('register') }}">Créer un compte</a>
</footer>

<script>
function setBilling(type) {
    const isYearly = type === 'yearly';
    document.getElementById('btnMonthly').classList.toggle('active', !isYearly);
    document.getElementById('btnYearly').classList.toggle('active', isYearly);

    document.querySelectorAll('.monthly-price').forEach(el => {
        const val = isYearly ? el.dataset.yearly : el.dataset.monthly;
        const suffix = isYearly ? ' XOF / mois' : ' XOF / mois';
        el.innerHTML = val + ' <span>' + suffix + '</span>';
    });
    document.querySelectorAll('.monthly-eur').forEach(el => {
        el.textContent = (isYearly ? el.dataset.yearly : el.dataset.monthly) + ' / mois';
    });
}

function toggleFaq(el) {
    const answer = el.nextElementSibling;
    const icon   = el.querySelector('i');
    const open   = answer.style.display === 'block';
    answer.style.display = open ? 'none' : 'block';
    icon.style.transform = open ? '' : 'rotate(180deg)';
}
</script>
</body>
</html>
