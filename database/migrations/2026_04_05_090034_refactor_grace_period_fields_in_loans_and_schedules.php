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
            $table->renameColumn('grace_period_end_date', 'grace_days');
        });

        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->dropColumn('is_grace_period');
            $table->date('grace_period_end_date')->nullable()->after('paid_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_schedules', function (Blueprint $table) {
            $table->dropColumn('grace_period_end_date');
            $table->boolean('is_grace_period')->default(false)->after('paid_date');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->renameColumn('grace_days', 'grace_period_end_date');
        });
    }
};
