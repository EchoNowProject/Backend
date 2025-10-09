<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('conversation_id');
            $table->integer('user_id');
            $table->timestamp('joined_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_participants');
    }
};
