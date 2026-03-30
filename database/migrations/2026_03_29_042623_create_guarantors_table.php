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
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('national_id', 50);
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable(); // [v2]
            $table->string('relationship', 100)->nullable();
            $table->string('document_path', 500)->nullable(); // [v2]

            $table->enum('status', ['active', 'released', 'defaulted'])
                ->default('active'); // [v2]

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
