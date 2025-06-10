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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('address');
            $table->decimal('rating', 2, 1)->default(0); // Rating out of 5
            $table->integer('number_of_reviews')->default(0);
            $table->decimal('price_per_night', 10, 2);
            $table->json('images'); // Will store array of image URLs [main_image, sub_image1, sub_image2, sub_image3, sub_image4]
            $table->text('description');
            $table->json('amenities')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->unsignedTinyInteger('stars')->nullable();
            $table->integer('available_rooms')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
