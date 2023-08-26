<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams_has_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_exam_id')->index()->comment('id của cuộc thì');
            $table->unsignedBigInteger('quiz_exam_question_id')->index()->comment('id của của câu hỏi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams_has_questions');
    }
};
