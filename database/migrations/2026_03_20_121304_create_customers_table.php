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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Male');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('national_id', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('age_verified')->default(0);
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 18, 2)->nullable();
            $table->boolean('has_existing_loan')->default(0);
            $table->smallInteger('credit_score')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('type', 50);
            $table->boolean('status')->default(1);
            $table->string('document_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
