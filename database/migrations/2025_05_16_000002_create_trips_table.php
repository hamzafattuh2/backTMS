<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('trips', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('guide_id')->nullable()->constrained('tour_guides')->onDelete('set null');

    // المعلومات الأساسية
    $table->string('name');
    $table->string('city'); // مدينة الرحلة
    $table->text('overview'); // نظرة عامة
    $table->text('short_overview'); // نظرة مختصرة

    // الصور
    $table->string('main_image'); // الصورة الرئيسية
    $table->json('gallery_images')->nullable(); // 4 صور فرعية (كمصفوفة JSON)

    // تفاصيل الرحلة
    $table->dateTime('start_at')->nullable();
    $table->dateTime('end_at')->nullable();
    $table->string('language', 50)->nullable();
    $table->unsignedSmallInteger('duration_days')->nullable();
    $table->decimal('price_per_night', 10, 2)->nullable();
    $table->unsignedInteger('available_seats')->default(1); // المقاعد المتاحة

    // الحالة والإعدادات
    $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
    $table->enum('visibility', ['public', 'private'])->default('public');
    $table->boolean('is_removable')->default(true);
    $table->boolean('is_guide_confirmed')->default(false);

    $table->timestamps();
});
    }
 public function down()
{
    Schema::table('trips', function (Blueprint $table) {
        // إسقاط أي قيود أجنبية مرتبطة بجدول trips
        $table->dropForeign(['user_id']);
        $table->dropForeign(['guide_id']);
    });

    Schema::dropIfExists('trips');
}
};
