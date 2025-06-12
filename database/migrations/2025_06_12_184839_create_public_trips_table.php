<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_trips', function (Blueprint $table) {
          $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('guide_id')->nullable()->constrained('tour_guides')->onDelete('set null');

    // المعلومات الأساسية
    $table->string('name');
    $table->string('city');
    $table->text('overview');
    $table->text('short_overview');

    // التعديلات الجديدة:
    $table->json('images')->nullable(); // حقل الصور
    $table->dateTime('date_of_tour')->nullable(); // تاريخ ووقت الجولة
    $table->string('meeting_point')->nullable(); // نقطة التجمع

    $table->string('language', 50)->nullable();
    $table->decimal('price_per_person', 10, 2)->nullable();
    $table->unsignedInteger('available_seats')->default(1);

    // الحالة والإعدادات
    $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
    $table->enum('visibility', ['public', 'private'])->default('public');
    $table->boolean('is_removable')->default(true);
    $table->boolean('is_guide_confirmed')->default(false);

    $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('public_trips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['guide_id']);
        });

        Schema::dropIfExists('public_trips');
    }
};
