<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('channel')->default('sms');       // sms | whatsapp | voice
            $table->string('direction');                     // inbound | outbound
            $table->string('status')->default('queued');     // queued | sent | delivered | failed | received
            $table->string('from_number');
            $table->string('to_number');
            $table->text('body')->nullable();
            $table->string('twilio_sid')->nullable()->unique();
            $table->json('twilio_payload')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'created_at']);
            $table->index(['contact_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
