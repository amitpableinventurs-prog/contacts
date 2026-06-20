<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_login_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->index();
            $table->string('ip_address', 45)->index();
            $table->string('user_agent', 255)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_login_logs');
    }
};
