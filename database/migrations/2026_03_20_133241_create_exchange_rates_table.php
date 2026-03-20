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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency');   // រូបិយប័ណ្ណមូលដ្ឋាន
            $table->string('target_currency'); // រូបិយប័ណ្ណគោលដៅ
            $table->decimal('rate', 15, 4);    // អត្រាប្តូរប្រាក់
            $table->date('exchange_date');     // កាលបរិច្ឆេទ
            $table->string('source')->nullable(); // ប្រភព
            $table->string('created_by');      // បង្កើតដោយ
            $table->boolean('status')->default(1); // ស្ថានភាព
            $table->string('document')->nullable(); // ឯកសារ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
