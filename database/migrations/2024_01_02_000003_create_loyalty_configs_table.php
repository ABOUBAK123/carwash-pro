<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carwash_id')->constrained()->onDelete('cascade');
            $table->integer('required_visits')->default(10);
            $table->decimal('discount_percentage', 5, 2)->default(10.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('loyalty_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carwash_id')->constrained()->onDelete('cascade');
            $table->string('vehicle_plate');
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->integer('visits_count')->default(0);
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamps();
            $table->unique(['carwash_id', 'vehicle_plate']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_visits');
        Schema::dropIfExists('loyalty_configs');
    }
};
