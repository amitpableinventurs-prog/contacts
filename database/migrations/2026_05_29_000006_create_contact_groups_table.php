<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id')->index();

            $table->string('name');
            $table->string('slug')->nullable()->index();

            $table->timestamps();
        });

        Schema::create('contact_contact_group', function (Blueprint $table) {
            $table->foreignId('team_id')->index();
            $table->foreignId('contact_id')->index();
            $table->foreignId('contact_group_id')->index();

            $table->primary(['contact_id', 'contact_group_id']);

            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->foreign('contact_group_id')->references('id')->on('contact_groups')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_contact_group');
        Schema::dropIfExists('contact_groups');
    }
};
