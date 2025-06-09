<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed']);
            $table->date('start_date')->nullable();
             $table->date('end_date')->nullable();
            $table->date('booking_date')->nullable();
            $table->text('special_requests')->nullable();
            $table->enum('payment_status', ['pay', 'not pay']);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('booking_hotels');
    }
};
