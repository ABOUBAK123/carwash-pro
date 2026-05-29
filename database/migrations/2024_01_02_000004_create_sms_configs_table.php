<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sms_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carwash_id')->constrained()->onDelete('cascade');
            $table->string('provider')->default('custom');
            $table->string('api_key')->nullable();
            $table->string('sender_name')->default('CarWash');
            $table->boolean('auto_send')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_configs');
    }
};
