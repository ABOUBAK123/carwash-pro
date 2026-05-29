<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carwash_id');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('duration'); // en minutes
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('carwash_id')->references('id')->on('carwashes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
