<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('private_trip_requests', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            // $table->foreignId('tourist_id')->constrained('tourist')->cascadeOnDelete();
            // $table->foreignId('guide_id')->constrained('tour_guide')->cascadeOnDelete();
            // $table->string('title_request')->nullable();
            // $table->string('status')->default('pending');
            $table->timestamps();
            // $table->unique(['trip_id', 'guide_id']);
        });

    }

    public function down()
    {
        Schema::table('private_trip_requests', function (Blueprint $table) {
            // 1. أولاً إسقاط القيود الأجنبية
            $table->dropForeign(['trip_id']);
            $table->dropForeign(['guide_id']);
            $table->dropForeign(['tourist_id']);
        });

        // 2. ثم إسقاط الجدول
        Schema::dropIfExists('private_trip_requests');
    }
};
