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
        Schema::table('diaries', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'friends', 'private'])
                  ->default('public')
                  ->after('body') // 位置はお好み
                  ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diaries', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
};
