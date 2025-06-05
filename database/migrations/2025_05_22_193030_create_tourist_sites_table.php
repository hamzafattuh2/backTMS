    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('tourist_sites', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // اسم المكان
                $table->string('main_image'); // الصورة الأساسية
                $table->json('gallery_images')->nullable(); // معرض الصور
                $table->text('address'); // العنوان
                $table->text('description'); // الوصف
                $table->enum('category', ['popular', 'nature', 'outdoors', 'historic', 'landmarks']); // التصنيف
                $table->integer('views_count')->default(0); // عدد المشاهدات
                $table->float('average_rating')->nullable(); // متوسط التقييم
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('tourist_sites');
        }
    };
