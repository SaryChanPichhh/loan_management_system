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
            $table->string('code')->unique(); // លេខកូដ
            $table->string('name'); // ឈ្មោះអតិថិជន
            $table->enum('gender', ['Male', 'Female']); // ភេទ
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('type'); // ប្រភេទ
            $table->boolean('status')->default(1); // ស្ថានភាព
            $table->string('document')->nullable(); // ឯកសារ (file)
            $table->timestamps();
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
