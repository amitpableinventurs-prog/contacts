<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->date('birthday')->nullable();

            $table->string('lifecycle_stage')->nullable(); // lead | customer | partner | etc.

            // Social
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();

            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();

            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['team_id', 'name']);
            $table->index(['team_id', 'email']);
            $table->index(['team_id', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
