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
        Schema::create('loan_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')
                ->unique() // UNIQUE (loan_id)
                ->constrained('loans')
                ->restrictOnDelete();

            $table->string('account_number', 50)->unique();

            $table->decimal('outstanding_balance', 18, 2)->default(0);
            $table->decimal('total_principal_paid', 18, 2)->default(0);
            $table->decimal('total_interest_paid', 18, 2)->default(0);
            $table->decimal('total_penalty_paid', 18, 2)->default(0);
            $table->decimal('total_late_fee_paid', 18, 2)->default(0); // [v2]

            $table->decimal('overdue_amount', 18, 2)->default(0);

            $table->unsignedSmallInteger('days_past_due')->default(0);

            $table->timestamp('last_payment_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_accounts');
    }
};
