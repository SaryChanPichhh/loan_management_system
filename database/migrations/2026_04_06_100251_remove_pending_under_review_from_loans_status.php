<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First map existing pending/under_review to approved to avoid data loss/errors
        DB::table('loans')
            ->whereIn('status', ['pending', 'under_review'])
            ->update(['status' => 'approved']);

        Schema::table('loans', function (Blueprint $table) {
            $table->enum('status', [
                'approved',
                'rejected',
                'active',
                'completed',
                'defaulted',
                'written_off'
            ])->default('approved')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('status', [
                'pending','under_review','approved',
                'rejected','active','completed',
                'defaulted','written_off'
            ])->default('pending')->change();
        });
    }
};
