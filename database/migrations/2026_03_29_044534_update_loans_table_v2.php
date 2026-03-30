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
        Schema::table('loans', function (Blueprint $table) {

            // New columns
            $table->foreignId('application_id')->nullable()->after('loan_code');
            $table->foreignId('product_id')->nullable()->after('customer_id');

            $table->decimal('disbursed_amount', 18, 2)->nullable()->after('principal_amount');

            $table->date('first_payment_date')->nullable();
            $table->date('grace_period_end_date')->nullable();

            $table->boolean('collateral_required')->default(false);
            $table->boolean('guarantor_required')->default(false);

            $table->date('early_settlement_date')->nullable();

            $table->foreignId('approved_by')->nullable();
            $table->foreignId('rejected_by')->nullable();
            $table->text('rejected_reason')->nullable();

            $table->foreignId('created_by')->nullable();

            // Modify existing columns
            $table->decimal('interest_rate', 7, 4)->change();
            $table->smallInteger('duration_months')->change();

            // Replace enum (IMPORTANT: requires doctrine/dbal)
            $table->enum('status', [
                'pending','under_review','approved',
                'rejected','active','completed',
                'defaulted','written_off'
            ])->default('pending')->change();

            // Rename note → keep if already exists
            $table->text('purpose')->nullable()->after('status');

            // Soft delete
            $table->softDeletes();
        });

        // Foreign keys (separate for clarity)
        Schema::table('loans', function (Blueprint $table) {

            $table->foreign('application_id')
                ->references('id')->on('loan_applications')
                ->nullOnDelete();

            $table->foreign('product_id')
                ->references('id')->on('loan_products')
                ->nullOnDelete();

            $table->foreign('approved_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('rejected_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {

            // Drop foreign keys first
            $table->dropForeign(['application_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['created_by']);

            // Drop columns
            $table->dropColumn([
                'application_id',
                'product_id',
                'disbursed_amount',
                'first_payment_date',
                'grace_period_end_date',
                'collateral_required',
                'guarantor_required',
                'early_settlement_date',
                'approved_by',
                'rejected_by',
                'rejected_reason',
                'purpose',
                'created_by',
                'deleted_at'
            ]);
        });
    }
};
