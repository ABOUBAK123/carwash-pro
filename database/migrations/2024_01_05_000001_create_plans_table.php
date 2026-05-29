<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 20)->unique();
            $table->string('name', 60);
            $table->string('description')->nullable();
            $table->decimal('price_monthly_xof', 10, 2)->default(0);
            $table->decimal('price_monthly_eur', 8, 2)->default(0);
            $table->integer('max_employees')->default(3);  // -1 = illimité
            $table->integer('max_invoices')->default(100); // -1 = illimité
            $table->integer('trial_days')->default(0);
            $table->string('badge', 30)->nullable();
            $table->string('color', 10)->default('#64748b');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Insérer les plans par défaut
        $plans = [
            [
                'slug'              => 'trial',
                'name'              => 'Essai gratuit',
                'description'       => 'Découvrez toutes les fonctionnalités sans engagement.',
                'price_monthly_xof' => 0,
                'price_monthly_eur' => 0,
                'max_employees'     => 3,
                'max_invoices'      => 100,
                'trial_days'        => 14,
                'badge'             => null,
                'color'             => '#64748b',
                'sort_order'        => 0,
                'features'          => json_encode([
                    ['label' => '1 centre de lavage',    'included' => true],
                    ['label' => '3 employés maximum',    'included' => true],
                    ['label' => '100 factures / mois',   'included' => true],
                    ['label' => 'Rendez-vous & clients', 'included' => true],
                    ['label' => 'Notifications SMS',     'included' => false],
                    ['label' => 'Analytics & profits',   'included' => false],
                    ['label' => 'Programme de fidélité', 'included' => false],
                    ['label' => 'Gestion équipements',   'included' => false],
                    ['label' => 'Support prioritaire',   'included' => false],
                ]),
            ],
            [
                'slug'              => 'starter',
                'name'              => 'Starter',
                'description'       => 'Pour les centres qui démarrent et professionnalisent leur gestion.',
                'price_monthly_xof' => 9900,
                'price_monthly_eur' => 14.99,
                'max_employees'     => 5,
                'max_invoices'      => 300,
                'trial_days'        => 0,
                'badge'             => null,
                'color'             => '#3b82f6',
                'sort_order'        => 1,
                'features'          => json_encode([
                    ['label' => '1 centre de lavage',         'included' => true],
                    ['label' => '5 employés',                 'included' => true],
                    ['label' => '300 factures / mois',        'included' => true],
                    ['label' => 'Rendez-vous & clients',      'included' => true],
                    ['label' => 'Notifications SMS',          'included' => true],
                    ['label' => 'Analytics & profits',        'included' => false],
                    ['label' => 'Programme de fidélité',      'included' => false],
                    ['label' => 'Gestion équipements',        'included' => false],
                    ['label' => 'Support prioritaire',        'included' => false],
                ]),
            ],
            [
                'slug'              => 'pro',
                'name'              => 'Pro',
                'description'       => 'La solution complète pour tout maîtriser.',
                'price_monthly_xof' => 19900,
                'price_monthly_eur' => 29.99,
                'max_employees'     => 15,
                'max_invoices'      => -1,
                'trial_days'        => 0,
                'badge'             => 'Populaire',
                'color'             => '#6366f1',
                'sort_order'        => 2,
                'features'          => json_encode([
                    ['label' => '1 centre de lavage',         'included' => true],
                    ['label' => '15 employés',                'included' => true],
                    ['label' => 'Factures illimitées',        'included' => true],
                    ['label' => 'Rendez-vous & clients',      'included' => true],
                    ['label' => 'Notifications SMS avancées', 'included' => true],
                    ['label' => 'Analytics & profits',        'included' => true],
                    ['label' => 'Programme de fidélité',      'included' => true],
                    ['label' => 'Gestion équipements',        'included' => true],
                    ['label' => 'Support prioritaire',        'included' => false],
                ]),
            ],
            [
                'slug'              => 'business',
                'name'              => 'Business',
                'description'       => 'Multi-centres et support dédié pour les grandes structures.',
                'price_monthly_xof' => 39900,
                'price_monthly_eur' => 59.99,
                'max_employees'     => -1,
                'max_invoices'      => -1,
                'trial_days'        => 0,
                'badge'             => null,
                'color'             => '#f59e0b',
                'sort_order'        => 3,
                'features'          => json_encode([
                    ['label' => '5 centres de lavage',        'included' => true],
                    ['label' => 'Employés illimités',         'included' => true],
                    ['label' => 'Factures illimitées',        'included' => true],
                    ['label' => 'Rendez-vous & clients',      'included' => true],
                    ['label' => 'Notifications SMS avancées', 'included' => true],
                    ['label' => 'Analytics & profits',        'included' => true],
                    ['label' => 'Programme de fidélité',      'included' => true],
                    ['label' => 'Gestion équipements',        'included' => true],
                    ['label' => 'Support dédié 24/7',         'included' => true],
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->insert(array_merge($plan, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
