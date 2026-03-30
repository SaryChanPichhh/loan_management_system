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
       Schema::create('loans', function (Blueprint $table) {
           $table->id();

           // Loan info
           $table->string('loan_code')->unique(); // LOAN-001
           $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

           // Money
           $table->decimal('principal_amount', 12, 2);
           $table->decimal('interest_rate', 5, 2); // %

           // Duration
           $table->integer('duration_months');

           // Status
           $table->enum('status', ['pending', 'active', 'completed', 'defaulted'])->default('pending');

           // Dates
           $table->date('start_date')->nullable();
           $table->date('end_date')->nullable();

           // Optional
           $table->text('note')->nullable();

           $table->timestamps();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
