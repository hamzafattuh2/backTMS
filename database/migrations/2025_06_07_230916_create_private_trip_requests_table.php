<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('private_trip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained('tour_guides')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('lang');
            $table->string('days');
            $table->integer('count_days');
            $table->enum('status',['pend','wait','accept'])->default('pending');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('private_trip_requests');
    }
};
