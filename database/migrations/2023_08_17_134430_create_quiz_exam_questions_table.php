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
        Schema::create('quiz_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_exam_id')->index();
            $table->string('title_origin')->comment('Tiêu đề câu hỏi');
            $table->string('image')->nullable()->comment('Hình ảnh câu hỏi');
            $table->text('title_extra')->nullable()->comment('Tiêu đề bổ sung, thằng này ở bên dưới ảnh');
            $table->text('explain')->nullable()->comment('Giải thích câu trả lời');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động của câu hỏi')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_exam_questions');
    }
};
