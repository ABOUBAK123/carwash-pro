<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carwashes', function (Blueprint $table) {
            $table->unsignedBigInteger('referred_by')->nullable()->after('manager_id');
            $table->foreign('referred_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commissionnaire_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('carwash_id')->constrained()->cascadeOnDelete();
            $table->string('plan_slug', 20);
            $table->decimal('subscription_amount_xof', 10, 2);
            $table->decimal('commission_amount_xof', 10, 2);
            $table->decimal('percentage', 5, 2)->default(3.00);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
        Schema::table('carwashes', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn('referred_by');
        });
    }
};
