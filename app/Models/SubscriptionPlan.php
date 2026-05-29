<?php

namespace App\Models;

class SubscriptionPlan
{
    public static array $plans = [
        'trial' => [
            'slug'           => 'trial',
            'name'           => 'Essai gratuit',
            'price_eur'      => 0,
            'price_xof'      => 0,
            'duration_days'  => 14,
            'badge'          => null,
            'color'          => '#64748b',
            'max_employees'  => 3,
            'max_invoices'   => 100,
            'features' => [
                ['label' => '1 centre de lavage',         'included' => true],
                ['label' => '3 employés maximum',         'included' => true],
                ['label' => '100 factures / mois',        'included' => true],
                ['label' => 'Rendez-vous & clients',      'included' => true],
                ['label' => 'Notifications SMS',          'included' => false],
                ['label' => 'Analytics & profits',        'included' => false],
                ['label' => 'Programme de fidélité',      'included' => false],
                ['label' => 'Gestion équipements',        'included' => false],
                ['label' => 'Support prioritaire',        'included' => false],
            ],
        ],
        'starter' => [
            'slug'           => 'starter',
            'name'           => 'Starter',
            'price_eur'      => 14.99,
            'price_xof'      => 9900,
            'duration_days'  => 30,
            'badge'          => null,
            'color'          => '#3b82f6',
            'max_employees'  => 5,
            'max_invoices'   => 300,
            'features' => [
                ['label' => '1 centre de lavage',         'included' => true],
                ['label' => '5 employés',                 'included' => true],
                ['label' => '300 factures / mois',        'included' => true],
                ['label' => 'Rendez-vous & clients',      'included' => true],
                ['label' => 'Notifications SMS',          'included' => true],
                ['label' => 'Analytics & profits',        'included' => false],
                ['label' => 'Programme de fidélité',      'included' => false],
                ['label' => 'Gestion équipements',        'included' => false],
                ['label' => 'Support prioritaire',        'included' => false],
            ],
        ],
        'pro' => [
            'slug'           => 'pro',
            'name'           => 'Pro',
            'price_eur'      => 29.99,
            'price_xof'      => 19900,
            'duration_days'  => 30,
            'badge'          => 'Populaire',
            'color'          => '#6366f1',
            'max_employees'  => 15,
            'max_invoices'   => -1,
            'features' => [
                ['label' => '1 centre de lavage',         'included' => true],
                ['label' => '15 employés',                'included' => true],
                ['label' => 'Factures illimitées',        'included' => true],
                ['label' => 'Rendez-vous & clients',      'included' => true],
                ['label' => 'Notifications SMS avancées', 'included' => true],
                ['label' => 'Analytics & profits',        'included' => true],
                ['label' => 'Programme de fidélité',      'included' => true],
                ['label' => 'Gestion équipements',        'included' => true],
                ['label' => 'Support prioritaire',        'included' => false],
            ],
        ],
        'business' => [
            'slug'           => 'business',
            'name'           => 'Business',
            'price_eur'      => 59.99,
            'price_xof'      => 39900,
            'duration_days'  => 30,
            'badge'          => null,
            'color'          => '#f59e0b',
            'max_employees'  => -1,
            'max_invoices'   => -1,
            'features' => [
                ['label' => '5 centres de lavage',        'included' => true],
                ['label' => 'Employés illimités',         'included' => true],
                ['label' => 'Factures illimitées',        'included' => true],
                ['label' => 'Rendez-vous & clients',      'included' => true],
                ['label' => 'Notifications SMS avancées', 'included' => true],
                ['label' => 'Analytics & profits',        'included' => true],
                ['label' => 'Programme de fidélité',      'included' => true],
                ['label' => 'Gestion équipements',        'included' => true],
                ['label' => 'Support dédié 24/7',         'included' => true],
            ],
        ],
    ];

    public static function get(string $slug): array
    {
        return static::$plans[$slug] ?? static::$plans['trial'];
    }

    public static function all(): array
    {
        return static::$plans;
    }

    public static function paid(): array
    {
        return array_filter(static::$plans, fn($p) => $p['price_eur'] > 0);
    }
}
