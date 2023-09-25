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
        Schema::table('quiz_exams', function (Blueprint $table) {
            //
            $table->foreignId('major_id')->nullable()->index();
            $table->foreignId('created_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_exams', function (Blueprint $table) {
            //
            $table->dropIndex(['major_id']);
            $table->dropIndex(['created_by']);
            $table->dropColumn('major_id');
            $table->dropColumn('created_by');
        });
    }
};
