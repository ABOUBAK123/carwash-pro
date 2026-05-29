<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('center_name');
            $table->string('owner_name');
            $table->string('email');
            $table->string('phone', 30);
            $table->text('address');
            $table->string('city', 100);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable();
            $table->text('services')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('terms_accepted')->default(false);
            $table->timestamp('terms_accepted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name', 80);
            $table->string('symbol', 10);
            $table->decimal('rate', 12, 6)->default(1.000000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('carwashes', function (Blueprint $table) {
            $table->string('currency', 5)->default('EUR')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('carwashes', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('registration_requests');
    }
};
