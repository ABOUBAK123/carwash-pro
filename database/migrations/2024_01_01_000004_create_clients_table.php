<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carwash_id');
            $table->string('name', 200);
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('vehicle_brand', 100)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->timestamps();

            $table->foreign('carwash_id')->references('id')->on('carwashes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
