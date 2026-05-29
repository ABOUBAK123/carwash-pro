<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->unsignedBigInteger('carwash_id');
            $table->enum('salary_type', ['hourly', 'fixed', 'commission'])->default('commission');
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('fixed_salary', 10, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->default(30.00);
            $table->integer('total_cars_washed')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('carwash_id')->references('id')->on('carwashes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
