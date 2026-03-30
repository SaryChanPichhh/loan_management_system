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
        // 1. First, expand the enum list to include 'pending' (temporarily keeping old values)
        DB::statement("ALTER TABLE loan_applications MODIFY COLUMN status ENUM('draft', 'submitted', 'pending', 'under_review', 'approved', 'rejected', 'cancelled') DEFAULT 'draft'");

        // 2. Now we can safely update existing data from 'draft' or 'submitted' to 'pending'
        DB::table('loan_applications')
            ->whereIn('status', ['draft', 'submitted'])
            ->update(['status' => 'pending']);

        // 3. Finally, modify the enum column definition to follow the new business rules (removing old values)
        DB::statement("ALTER TABLE loan_applications MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum back to its original state (including draft and submitted)
        DB::statement("ALTER TABLE loan_applications MODIFY COLUMN status ENUM('draft', 'submitted', 'under_review', 'approved', 'rejected', 'cancelled') DEFAULT 'draft'");
    }
};
