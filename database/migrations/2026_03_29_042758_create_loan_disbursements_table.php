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
        Schema::create('loan_disbursements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->restrictOnDelete();

            $table->decimal('amount', 18, 2);

            $table->enum('method', [
                'BANK_TRANSFER',
                'CASH',
                'MOBILE_MONEY',
                'CHEQUE'
            ]);

            $table->string('reference_number', 100)->nullable();
            $table->string('bank_name', 200)->nullable();
            $table->string('account_number', 100)->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('disbursed_at')
                ->useCurrent();

            $table->foreignId('disbursed_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_disbursements');
    }
};
