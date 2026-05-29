<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carwash_id');
            $table->string('client_name', 200)->nullable();
            $table->string('client_phone', 20)->nullable();
            $table->string('vehicle_brand', 100)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('service_name', 200)->nullable();
            $table->decimal('service_price', 8, 2)->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->decimal('employee_commission', 8, 2)->nullable();
            $table->decimal('total_amount', 8, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('paid');
            $table->string('invoice_number', 20)->unique();
            $table->timestamps();

            $table->foreign('carwash_id')->references('id')->on('carwashes')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
