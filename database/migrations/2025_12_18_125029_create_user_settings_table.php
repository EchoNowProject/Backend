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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->enum('theme', ['light', 'dark', 'system'])->default('dark');
            $table->boolean('notifications_enable')->default(true);
            $table->boolean('sound_enable')->default(true);
            $table->integer('volume')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
