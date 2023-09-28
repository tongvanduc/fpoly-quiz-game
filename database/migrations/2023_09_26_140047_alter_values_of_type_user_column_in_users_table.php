<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $arr = [TYPE_USER_SUPER_ADMIN, TYPE_USER_ADMIN, TYPE_USER_STUDENT];
            $values = array_map(function ($value) {
                return "'$value'";
            }, $arr);
            DB::statement("ALTER TABLE users MODIFY COLUMN type_user ENUM(" . implode(', ', $values) . ")");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $arr = [TYPE_USER_ADMIN, TYPE_USER_STUDENT];
            $values = array_map(function ($value) {
                return "'$value'";
            }, $arr);
            DB::statement("ALTER TABLE users MODIFY COLUMN type_user ENUM(" . implode(', ', $values) . ")");
        });
    }
};
