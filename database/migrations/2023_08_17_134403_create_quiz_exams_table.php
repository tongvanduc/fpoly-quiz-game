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
        Schema::create('quiz_exams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên cuộc thi');
            $table->string('image')->nullable()->comment('Ảnh đại diện');
            $table->string('code')->unique()->comment('Mã cuộc thi')->index();
            $table->dateTime('start_date')->nullable()->comment('Thời gian bắt đầu')->index();
            $table->dateTime('end_date')->nullable()->comment('Thời gian kết thúc')->index();
            $table->unsignedTinyInteger('timer')->nullable()->comment('Thời gian làm 1 câu, tính bằng giây');
            $table->unsignedTinyInteger('max_of_tries')->nullable()->comment('Số lần làm tối đa');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động của cuộc thi')->index();
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['start_date', 'end_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_exams');
    }
};
