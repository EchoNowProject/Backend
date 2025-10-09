<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('display_name')->nullable();
            $table->text('biography')->nullable();
            $table->string('avatar_img')->nullable();
            $table->boolean('verified')->default(false);
            $table->integer('status');
            $table->integer('plan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
