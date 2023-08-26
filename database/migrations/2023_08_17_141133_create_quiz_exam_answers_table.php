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
        Schema::create('quiz_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_exam_question_id')->index();
            $table->text('content')->comment('Nội dung câu trả lời');
            $table->unsignedTinyInteger('order')->default(0)->comment('Sắp xếp câu trả lời');
            $table->boolean('is_true')->default(false)->comment('Đáp án');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động của câu trả lời')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_exam_answers');
    }
};
