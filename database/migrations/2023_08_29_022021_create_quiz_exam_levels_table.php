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
        Schema::create('quiz_exam_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('tên các cấp độ thi trắc nghiệm');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động của cấp độ thi trắc nghiệm')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_exam_levels');
    }
};
