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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('reference_type')->nullable(); // e.g., 'Loan', 'Repayment'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->decimal('total_amount', 15, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('journal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->onDelete('cascade');
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->enum('type', ['Debit', 'Credit']);
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
