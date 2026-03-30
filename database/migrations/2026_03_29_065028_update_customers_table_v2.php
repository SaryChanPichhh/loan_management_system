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
        // 1. ADD / MODIFY columns first
        Schema::table('customers', function (Blueprint $table) {

            $table->enum('gender', ['Male', 'Female', 'Other'])
                ->default('Male')
                ->change();

            $table->string('phone', 30)->change();
            $table->string('type', 50)->change();

            $table->string('email')->nullable()->after('phone');
            $table->string('national_id', 50)->nullable()->after('address');
            $table->date('date_of_birth')->nullable()->after('national_id');

            $table->boolean('age_verified')->default(0)->after('date_of_birth');
            $table->string('occupation')->nullable()->after('age_verified');
            $table->decimal('monthly_income', 18, 2)->nullable()->after('occupation');

            $table->boolean('has_existing_loan')->default(0)->after('monthly_income');

            $table->smallInteger('credit_score')->nullable()->after('has_existing_loan');

            $table->renameColumn('document', 'document_path');

            $table->softDeletes();

            // ✅ IMPORTANT: create column FIRST
            $table->unsignedBigInteger('created_by')->nullable()->after('credit_score');
        });

        // 2. ADD foreign key AFTER column exists
        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {

            $table->dropForeign(['created_by']);

            $table->dropColumn([
                'email',
                'national_id',
                'date_of_birth',
                'age_verified',
                'occupation',
                'monthly_income',
                'has_existing_loan',
                'credit_score',
                'deleted_at',
            ]);

            $table->renameColumn('document_path', 'document');

            $table->enum('gender', ['Male', 'Female'])->change();
        });
    }
};
