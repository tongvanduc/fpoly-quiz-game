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
                '1' => [
                    'quiz_contest_answer_ids' => [1, 2, 3],
                    'correct_answers' => [2, 3]
                ],
                '2' => [
                    'quiz_contest_answer_ids' => [5, 7],
                    'correct_answers' => [4, 5]
                ],
            ];
             * */
            $table->json('results')
                ->comment('Cấu trúc json:
                    {"1":{"quiz_contest_answer_ids":[1,2,3],"correct_answers":[2,3]},"2":{"quiz_contest_answer_ids":[5,7],"correct_answers":[4,5]}};
                    key: lưu ID của câu hỏi, bên trong lưu 2 mảng: quiz_contest_answer_ids là mảng kết quả chọn của thí sinh, correct_answers: mảng lưu câu trả lời đúng
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
