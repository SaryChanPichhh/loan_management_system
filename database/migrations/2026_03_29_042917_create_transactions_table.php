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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('loan_accounts')
                ->restrictOnDelete();

            $table->enum('type', [
                'DISBURSEMENT',
                'REPAYMENT_PRINCIPAL',
                'REPAYMENT_INTEREST',
                'REPAYMENT_PENALTY',
                'REPAYMENT_LATE_FEE', // [v2]
                'FEE',
                'REVERSAL',
                'ADJUSTMENT',
                'WRITE_OFF',
            ]);

            $table->decimal('amount', 18, 2);
            $table->decimal('running_balance', 18, 2);

            $table->string('reference', 100)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('created_at')
                ->useCurrent();

            // Indexes
            $table->index('account_id', 'idx_transactions_account');
            $table->index('type', 'idx_transactions_type');
            $table->index('created_at', 'idx_transactions_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
