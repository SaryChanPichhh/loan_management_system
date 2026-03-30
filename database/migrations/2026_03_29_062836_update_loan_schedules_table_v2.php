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
        Schema::table('loan_schedules', function (Blueprint $table) {

            // New structure
            $table->smallInteger('installment_number')->after('loan_id');

            $table->decimal('principal_due', 18, 2)->default(0);
            $table->decimal('interest_due', 18, 2)->default(0);
            $table->decimal('penalty_due', 18, 2)->default(0);
            $table->decimal('late_fee_due', 18, 2)->default(0);

            $table->date('paid_date')->nullable();
            $table->boolean('is_grace_period')->default(false);

            // Modify existing columns
            $table->decimal('amount_due', 18, 2)->nullable()->change(); // optional: you may remove later
            $table->decimal('amount_paid', 18, 2)->default(0)->change();

            // Update enum (requires doctrine/dbal)
            $table->enum('status', [
                'pending','partial','paid','overdue','waived'
            ])->default('pending')->change();

        });

        // Unique constraint
        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->unique(['loan_id', 'installment_number'], 'uq_schedule_installment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_schedules', function (Blueprint $table) {

            $table->dropUnique('uq_schedule_installment');

            $table->dropColumn([
                'installment_number',
                'principal_due',
                'interest_due',
                'penalty_due',
                'late_fee_due',
                'paid_date',
                'is_grace_period',
            ]);
        });
    }
};
