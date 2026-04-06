<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clear existing date values before converting to integer type
        DB::statement('UPDATE loans SET grace_period_end_date = NULL WHERE grace_period_end_date IS NOT NULL');
        DB::statement('ALTER TABLE loans MODIFY COLUMN grace_period_end_date TINYINT UNSIGNED NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE loans MODIFY COLUMN grace_period_end_date DATE NULL DEFAULT NULL');
    }
};
