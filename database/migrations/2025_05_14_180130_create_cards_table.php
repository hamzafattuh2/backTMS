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
       Schema::create('cards', function (Blueprint $table) {
        $table->id();
        $table->string('card_number', 16)->unique(); // رقم البطاقة (16 رقم)
        $table->string('expire_time', 5); // MM/YY
        $table->string('cvv', 3); // رمز الأمان
        $table->string('card_holder'); // اسم صاحب البطاقة
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
