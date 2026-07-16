<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->json('changes'); // field => proposed new value
            $table->json('original'); // field => current value, snapshot at request time
            $table->json('tags')->nullable(); // proposed tag id list, null = unchanged
            $table->string('photo_path')->nullable(); // staged upload on the local disk, awaiting approval
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['contact_id', 'status']);
            $table->index(['team_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_edit_requests');
    }
};
