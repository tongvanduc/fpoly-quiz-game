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
        Schema::table('quiz_exam_questions', function (Blueprint $table) {
            $table->dropIndex(['quiz_exam_id']);
            $table->dropColumn('quiz_exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_exam_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('quiz_exam_id')->index();
        });
    }
};
