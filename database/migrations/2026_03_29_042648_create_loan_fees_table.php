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
        Schema::create('loan_fees', function (Blueprint $table) {
            $table->id();

            // Foreign key to loans
            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            $table->string('fee_type', 100); // application | processing | insurance

            $table->decimal('amount', 18, 2);

            $table->boolean('is_waived')->default(false);

            // Nullable FK to users (waived_by)
            $table->foreignId('waived_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('waived_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_fees');
    }
};
