<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_public_key')->nullable();
            $table->string('stripe_secret_key')->nullable();
            $table->string('paypal_client_id')->nullable();
            $table->string('merchant_account')->nullable();
            $table->string('webhook_url')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(29.99);
            $table->decimal('yearly_price', 10, 2)->default(299.99);
            $table->string('currency', 5)->default('EUR');
            $table->boolean('enable_mobile_payment')->default(true);
            $table->string('orange_money_api_key')->nullable();
            $table->string('mtn_momo_api_key')->nullable();
            $table->string('moov_money_api_key')->nullable();
            $table->string('wave_api_key')->nullable();
            $table->timestamps();
        });

        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('smtp_host')->default('smtp.gmail.com');
            $table->integer('smtp_port')->default(587);
            $table->string('smtp_user')->nullable();
            $table->string('smtp_password')->nullable();
            $table->enum('smtp_encryption', ['tls', 'ssl', 'none'])->default('tls');
            $table->string('from_email')->nullable();
            $table->string('from_name')->default('CarWash Pro');
            $table->boolean('enable_notifications')->default(true);
            $table->timestamps();
        });

        Schema::create('terms_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms_settings');
        Schema::dropIfExists('email_settings');
        Schema::dropIfExists('payment_settings');
    }
};
