<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_chair_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('number_of_chairs');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed']);
            $table->date('booking_date');
            $table->text('special_requests')->nullable();
            $table->enum('payment_status', ['pay', 'not pay']);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('booking_chair_trips');
    }
}; 