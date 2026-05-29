<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carwash_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['washing_machine', 'vacuum', 'compressor', 'pressure_washer', 'other']);
            $table->date('purchase_date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->enum('status', ['available', 'maintenance', 'broken'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
