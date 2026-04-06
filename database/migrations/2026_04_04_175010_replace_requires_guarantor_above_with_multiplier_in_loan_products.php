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
        Schema::table('loan_products', function (Blueprint $table) {
            $table->decimal('guarantor_income_multiplier', 5, 2)->default(1.5)->after('max_term_months');
            $table->dropColumn('requires_guarantor_above');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_products', function (Blueprint $table) {
            $table->decimal('requires_guarantor_above', 18, 2)->default(500)->after('late_fee_rate');
            $table->dropColumn('guarantor_income_multiplier');
        });
    }
};
