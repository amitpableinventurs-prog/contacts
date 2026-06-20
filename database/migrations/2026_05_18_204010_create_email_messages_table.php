<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('from_email');
            $table->string('to_email');
            $table->string('subject');
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();

            $table->string('status')->default('queued');    // queued | sending | sent | bounced | failed
            $table->string('tracking_id')->nullable()->unique();
            $table->unsignedInteger('opens_count')->default(0);
            $table->unsignedInteger('clicks_count')->default(0);
            $table->timestamp('first_opened_at')->nullable();
            $table->timestamp('last_opened_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'created_at']);
            $table->index(['contact_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
