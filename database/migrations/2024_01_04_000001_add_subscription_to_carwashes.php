<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carwashes', function (Blueprint $table) {
            $table->string('plan', 20)->default('trial')->after('is_active');
            $table->enum('subscription_status', ['trial', 'active', 'expired', 'cancelled'])->default('trial')->after('plan');
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_status');
            $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('carwashes', function (Blueprint $table) {
            $table->dropColumn(['plan', 'subscription_status', 'trial_ends_at', 'subscription_ends_at']);
        });
    }
};
