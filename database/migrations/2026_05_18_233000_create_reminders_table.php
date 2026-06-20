<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('remind_at');
            $table->string('status')->default('pending'); // pending | completed | cancelled
            $table->boolean('notify_email')->default(true);
            $table->boolean('notify_sms')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'remind_at']);
            $table->index(['status', 'remind_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
