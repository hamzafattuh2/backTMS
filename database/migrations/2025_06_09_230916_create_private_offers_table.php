<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('private_offers', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            // $table->foreignId('trip_request_id')->nullable()->constrained('private_trip_requests')->cascadeOnDelete();
            $table->integer('price');
            $table->timestamps();
        });

    }

    public function down()
    {

        Schema::dropIfExists('private_offers');
    }
};
