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

    $table->string('title')->nullable(); // تعديل: جعل العنوان nullable
    $table->text('description');

    $table->dateTime('start_date');
    $table->dateTime('end_date')->nullable(); // تعديل: جعل تاريخ النهاية nullable

    $table->string('languageOfTrip');
    $table->integer('days_count');
    $table->decimal('price', 10, 2)->nullable();

    $table->string('status')->nullable(); // تعديل: جعل الحالة nullable

    $table->enum('public_or_private', ['public', 'private'])->nullable(); // تعديل: جعل النوع nullable

    $table->boolean('delete_able')->default(true);
    $table->boolean('confirm_by_Guide')->default(false );
    $table->timestamps(); // ينصح بوجود timestamps للتتبع
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
