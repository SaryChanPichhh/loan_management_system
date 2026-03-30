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
        Schema::table('repayments', function (Blueprint $table) {

            // Remove old useless fields (IMPORTANT cleanup)
            $table->dropColumn([
                'loan_reference',
                'customer_name',
                'points'
            ]);

            // New relations
            $table->foreignId('schedule_id')->nullable()->after('loan_id');

            // Upgrade amount precision
            $table->decimal('amount', 18, 2)->change();

            // Payment breakdown (CRITICAL)
            $table->decimal('principal_paid', 18, 2)->default(0);
            $table->decimal('interest_paid', 18, 2)->default(0);
            $table->decimal('penalty_paid', 18, 2)->default(0);
            $table->decimal('late_fee_paid', 18, 2)->default(0);

            // Flags
            $table->boolean('late_fee_applied')->default(false);
            $table->boolean('is_early_settlement')->default(false);

            // Improve existing fields
            $table->string('payment_method', 50)->change();
            $table->string('reference_number', 191)->nullable()->change();

            // Status update (ENUM → better use string)
            $table->string('status')->default('pending')->change();

            // Staff tracking
            $table->foreignId('received_by')->nullable();
            $table->foreignId('waived_by')->nullable();
        });

        // Foreign keys (separate block)
        Schema::table('repayments', function (Blueprint $table) {

            $table->foreign('schedule_id')
                ->references('id')->on('loan_schedules')
                ->nullOnDelete();

            $table->foreign('received_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('waived_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            // Optional: adjust loan FK behavior (RESTRICT instead of cascade)
            $table->dropForeign(['loan_id']);
            $table->foreign('loan_id')
                ->references('id')->on('loans')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('repayments', function (Blueprint $table) {

            // Drop FKs first
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['received_by']);
            $table->dropForeign(['waived_by']);
            $table->dropForeign(['loan_id']);

            // Drop new columns
            $table->dropColumn([
                'schedule_id',
                'principal_paid',
                'interest_paid',
                'penalty_paid',
                'late_fee_paid',
                'late_fee_applied',
                'is_early_settlement',
                'received_by',
                'waived_by',
            ]);

            // Restore old FK behavior
            $table->foreign('loan_id')
                ->references('id')->on('loans')
                ->cascadeOnDelete();
        });
    }
};
