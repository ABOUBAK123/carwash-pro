<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carwash_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['electricity', 'water', 'products', 'maintenance', 'salary', 'other']);
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->date('expense_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
