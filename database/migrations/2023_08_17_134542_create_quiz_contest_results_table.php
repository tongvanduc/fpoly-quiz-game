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
        Schema::create('quiz_contest_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('quiz_contest_id')->index();

            /*
             $results = [
                '1' => [1, 2, 3],
                '2' => [5, 7],
            ];
             * */
            $table->json('results')
                ->comment('Cấu trúc json:
                    {"1":[1,2,3],"2":[5,7]}
                    key: lưu ID của câu hỏi, value là mảng kết quả chọn của thí sinh
                ');

            $table->unsignedInteger('point')->comment('Tổng điểm làm được');
            $table->unsignedInteger('total_time')->comment('Tổng thời gian, tính bằng giấy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_contest_results');
    }
};
