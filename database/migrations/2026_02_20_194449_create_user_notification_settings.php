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

        //Esta tabla solo se le dara uso si el booleano del notifications_enable del usuario esta activo

        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('notify_friend_requests')->default(true);
            $table->boolean('sound_enabled')->default(true);
            $table->boolean('show_message_preview')->comment('Para comentar los pop-up, modales, toasts')->default(true);
            $table->boolean('notify_direct_messages')->default(true);
            // Actualmente para las entregas del proyecto no tiene valor pero mas adelante si lo tendra
            // Ya que no tenemos control de las menciones de momento 
            $table->boolean('notify_mentions')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_settings');
    }
};
