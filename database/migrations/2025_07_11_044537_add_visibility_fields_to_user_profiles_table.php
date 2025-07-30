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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->enum('nickname_visibility', ['public', 'friends', 'private'])->default('friends');
            $table->enum('bio_visibility', ['public', 'friends', 'private'])->default('friends');
            $table->enum('gender_visibility', ['public', 'friends', 'private'])->default('private');
            $table->enum('birthdate_visibility', ['public', 'friends', 'private'])->default('private');
            $table->enum('location_visibility', ['public', 'friends', 'private'])->default('friends');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            //
        });
    }
};
