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
        Schema::create('performance_testings', function (Blueprint $table) {
            $table->id();
            $table->string('field_1');
            $table->string('field_2');
            $table->string('field_3');
            $table->string('field_4');
            $table->string('field_5');
            $table->string('field_6');
            $table->string('field_7');
            $table->string('field_8');
            $table->string('field_9');
            $table->string('field_10');
            $table->string('field_11');
            $table->string('field_12');
            $table->string('field_13');
            $table->string('field_14');
            $table->string('field_15');
            $table->string('field_16');
            $table->string('field_17');
            $table->string('field_18');
            $table->string('field_19');
            $table->string('field_20');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_testings');
    }
};
