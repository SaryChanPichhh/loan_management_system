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
        Schema::create('loan_collaterals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->restrictOnDelete();

            $table->string('collateral_type', 100); // land_title | vehicle | equipment

            $table->text('description');

            $table->decimal('estimated_value', 18, 2); // >= loan * 1.20 (business rule)

            $table->date('valuation_date')->nullable();

            $table->enum('status', [
                'active',
                'released',
                'seized'
            ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_collaterals');
    }
};
