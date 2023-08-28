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
        Schema::table('exams_has_questions', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropIndex(['quiz_exam_id']);
            $table->dropIndex(['quiz_exam_question_id']);
            $table->primary(['quiz_exam_id', 'quiz_exam_question_id']);
            $table->dropTimestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams_has_questions', function (Blueprint $table) {
            $table->id();
            $table->index(['quiz_exam_id']);
            $table->index(['quiz_exam_question_id']);
            $table->timestamps();
        });
    }
};
