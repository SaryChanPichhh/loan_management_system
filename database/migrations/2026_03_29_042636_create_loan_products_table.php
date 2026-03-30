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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();

            $table->string('product_code', 30)->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('min_amount', 18, 2);
            $table->decimal('max_amount', 18, 2);

            $table->decimal('interest_rate', 7, 4); // monthly %

            $table->enum('interest_type', [
                'FLAT',
                'REDUCING_BALANCE',
                'COMPOUND'
            ]);

            $table->unsignedSmallInteger('max_term_months');

            $table->unsignedSmallInteger('grace_period_days')->default(3);

            $table->decimal('late_fee_rate', 7, 4)->default(1.5);

            $table->decimal('requires_guarantor_above', 18, 2)->default(500);

            $table->decimal('requires_collateral_above', 18, 2)->default(5000);

            $table->decimal('penalty_rate', 7, 4)->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
