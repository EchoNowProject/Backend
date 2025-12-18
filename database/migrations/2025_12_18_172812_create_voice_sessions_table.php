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
        Schema::create('voice_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('channel_id');
            $table->integer('user_id');
            $table->timestamp('joined_at');
            $table->timestamp('left_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_sessions');
    }
};
