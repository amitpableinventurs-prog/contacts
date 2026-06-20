<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ensures the contacts table has the full v2 schema.
// Guards with hasColumn() so it is safe to run even if columns already exist.
return new class extends Migration
{
    private array $newColumns = [
        'group_id', 'owner_id', 'phone', 'company', 'job_title', 'website',
        'address', 'photo', 'birthday', 'lifecycle_stage',
        'facebook', 'twitter', 'linkedin', 'notes', 'custom_fields',
        'last_contacted_at', 'deleted_at',
    ];

    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('contacts', 'group_id')) {
                $table->foreignId('group_id')->nullable()->after('team_id')->constrained('groups')->nullOnDelete();
            }
            if (! Schema::hasColumn('contacts', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->after('group_id')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('contacts', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (! Schema::hasColumn('contacts', 'company')) {
                $table->string('company')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'job_title')) {
                $table->string('job_title')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'website')) {
                $table->string('website')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'address')) {
                $table->string('address')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'photo')) {
                $table->string('photo')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'birthday')) {
                $table->date('birthday')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'lifecycle_stage')) {
                $table->string('lifecycle_stage')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'facebook')) {
                $table->string('facebook')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'twitter')) {
                $table->string('twitter')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'linkedin')) {
                $table->string('linkedin')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'custom_fields')) {
                $table->json('custom_fields')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'last_contacted_at')) {
                $table->timestamp('last_contacted_at')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $existing = collect($this->newColumns)->filter(fn ($c) => Schema::hasColumn('contacts', $c));
            if ($existing->contains('group_id')) {
                $table->dropConstrainedForeignId('group_id');
            }
            if ($existing->contains('owner_id')) {
                $table->dropConstrainedForeignId('owner_id');
            }
            $drop = $existing->reject(fn ($c) => in_array($c, ['group_id', 'owner_id', 'deleted_at']))->values()->all();
            if ($drop) {
                $table->dropColumn($drop);
            }
            if ($existing->contains('deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
