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
        Schema::create('server_user_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('server_id');
            $table->integer('user_id');
            $table->string('nickname')->nullable();
            $table->boolean('muted')->default(false);
            $table->integer('notifications_level')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_user_settings');
    }
};
