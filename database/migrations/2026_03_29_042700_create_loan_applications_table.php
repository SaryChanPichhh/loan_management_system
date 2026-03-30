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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            // Primary key (auto-increment)

            $table->string('application_code', 50)->unique();
            // Unique code for the application (human-readable reference)

            // Required relationships
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();
            // Reference to the customer who applies
            // to Prevent deleting customer if they have applications

            $table->foreignId('product_id')
                ->constrained('loan_products')
                ->restrictOnDelete();
            // Reference to loan product (defines rules like interest, limits)

            $table->decimal('requested_amount', 18, 2);
            // Amount the customer wants to borrow

            $table->unsignedSmallInteger('requested_months');
            // Loan duration in months (e.g., 12, 24)

            $table->text('purpose')->nullable();
            // Reason for loan (optional)

            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'cancelled'
            ])->default('draft');
            // Current status of application workflow
            // Default is 'draft' (not yet submitted)

            // Review info
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            // Staff/user who reviewed the application
            // If deleted, set this field to NULL

            $table->timestamp('reviewed_at')->nullable();
            // When the application was reviewed

            $table->text('rejection_reason')->nullable();
            // Reason for rejection (only used if status = rejected)

            // Link to loan (after approval)
            $table->foreignId('loan_id')
                ->nullable()
                ->constrained('loans')
                ->nullOnDelete();
            // Links to actual loan record after approval
            // NULL if not yet approved

            // Created by
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            // User who created the application (staff/system)

            $table->timestamps();
            // created_at = when record created
            // updated_at = last updated time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
