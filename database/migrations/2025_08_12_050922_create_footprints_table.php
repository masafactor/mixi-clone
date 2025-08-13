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
        Schema::create('footprints', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('viewer_id') // 見た人
                        ->constrained('users')
                        ->onDelete('cascade');
                    $table->foreignId('visited_user_id') // 見られた人
                        ->constrained('users')
                        ->onDelete('cascade');
                    $table->timestamps();

                    // 同じ人が何度も見た場合の制御用にユニーク制約をつけることも可能
                    // 1日1回だけ記録するなら以下コメント解除
                    // $table->unique(['viewer_id', 'visited_user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footprints');
    }
};
