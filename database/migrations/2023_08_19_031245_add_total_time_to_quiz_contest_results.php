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
        Schema::table('quiz_contest_results', function (Blueprint $table) {
            $table->unsignedInteger('total_time')->after('point')->comment('Tổng thời gian làm, tính bằng giây');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_contest_results', function (Blueprint $table) {
            $table->dropColumn('total_time');
        });
    }
};
