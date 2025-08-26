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
         Schema::table('footprints', function (Blueprint $table) {
            // 同日判定用のカラム追加
            $table->date('visited_on')->after('visited_user_id')->nullable()->index();

            // 同一ユーザー間で同日に複数レコードを作れないよう制約
            $table->unique(['viewer_id', 'visited_user_id', 'visited_on'], 'footprints_unique_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footprints', function (Blueprint $table) {
            $table->dropUnique('footprints_unique_per_day');
            $table->dropColumn('visited_on');
        });
    }
};
