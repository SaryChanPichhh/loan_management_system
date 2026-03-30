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
        Schema::create('loan_collateral_docs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('collateral_id')
                ->constrained('loan_collaterals')
                ->cascadeOnDelete();

            $table->string('document_type', 100); // land_title | vehicle_reg | photo

            $table->string('file_name', 255);
            $table->string('storage_path', 500);

            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size_bytes')->nullable();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('uploaded_at')
                ->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_collateral_docs');
    }
};
