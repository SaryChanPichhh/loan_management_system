<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();

            // Each installment
            $table->date('due_date');
            $table->decimal('amount_due', 12, 2);

            // Payment tracking
            $table->decimal('amount_paid', 12, 2)->default(0);

            $table->enum('status', ['pending', 'paid', 'late'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_schedules');
    }
};
