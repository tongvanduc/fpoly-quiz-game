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
            $table->foreignId('campus_major_id')->nullable();
            $table->foreignId('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_exams', function (Blueprint $table) {
            //
            $table->dropColumn('campus_major_id');
            $table->dropColumn('created_by');
        });
    }
};
