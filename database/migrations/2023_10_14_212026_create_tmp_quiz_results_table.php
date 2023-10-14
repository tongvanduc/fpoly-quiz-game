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
        Schema::create('tmp_quiz_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('quiz_exam_id')->index();
            $table->unsignedBigInteger('quiz_exam_question_id')->index();
            $table->string('code')->comment('Mã cuộc thi')->index();
            $table->json('answers')->comment('[1,2,3]: ID câu trả lời của sinh viên');
            $table->unsignedFloat('point')->comment('Điểm làm được của câu hỏi');
            $table->unsignedFloat('time')->comment('Thời gian làm của câu hỏi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmp_quiz_results');
    }
};
