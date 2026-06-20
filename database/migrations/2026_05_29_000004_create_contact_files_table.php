<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id')->index();
            $table->foreignId('contact_id')->index();
            $table->foreignId('user_id')->index();

            $table->string('file_name')->nullable();
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();

            $table->timestamps();
        });

        Schema::table('contact_files', function (Blueprint $table) {
            $table->foreign('contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_files');
    }
};
