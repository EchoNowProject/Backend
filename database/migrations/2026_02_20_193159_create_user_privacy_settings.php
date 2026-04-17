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
        Schema::create('user_privacy_settings', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->boolean('friend_request_permission')->default(true);
            $table->boolean('direct_message_permission')->default(true);
            $table->boolean('allow_search_by_email')->default(true);
            $table->boolean('allow_search_by_phone')->default(true);
            $table->boolean('show_online_status')->default(true);
            // Actualmente para las entregas del proyecto no tiene valor pero mas adelante si lo tendra
            $table->boolean('show_activity')->comment('Para mostrar que esta haciendo el usuario en ese instante')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_privacy_settings');
    }
};
