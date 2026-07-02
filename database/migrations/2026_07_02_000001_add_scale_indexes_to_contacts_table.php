<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Prepares the contacts table for large datasets (100k–500k rows):
//  - phone_digits: normalized (digits-only) phone for fast, indexed duplicate
//    matching during imports, replacing full-table scans.
//  - (team_id, approval_status): index for the pending-count query that runs
//    on every contacts page load.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('contacts', 'phone_digits')) {
                $table->string('phone_digits', 32)->nullable()->after('phone');
            }
        });

        Schema::table('contacts', function (Blueprint $table) {
            $indexes = collect(Schema::getIndexes('contacts'))->pluck('name');
            if (! $indexes->contains('contacts_team_id_phone_digits_index')) {
                $table->index(['team_id', 'phone_digits']);
            }
            if (! $indexes->contains('contacts_team_id_approval_status_index')) {
                $table->index(['team_id', 'approval_status']);
            }
        });

        // Backfill phone_digits for existing rows. REGEXP_REPLACE exists on
        // MySQL 8+ / MariaDB 10.0.5+; fall back to a PHP chunked update.
        try {
            DB::table('contacts')
                ->whereNotNull('phone')
                ->update(['phone_digits' => DB::raw("REGEXP_REPLACE(phone, '[^0-9]', '')")]);
        } catch (\Throwable) {
            DB::table('contacts')
                ->whereNotNull('phone')
                ->orderBy('id')
                ->chunkById(1000, function ($rows) {
                    foreach ($rows as $row) {
                        DB::table('contacts')->where('id', $row->id)
                            ->update(['phone_digits' => preg_replace('/\D/', '', (string) $row->phone)]);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $indexes = collect(Schema::getIndexes('contacts'))->pluck('name');
            if ($indexes->contains('contacts_team_id_phone_digits_index')) {
                $table->dropIndex(['team_id', 'phone_digits']);
            }
            if ($indexes->contains('contacts_team_id_approval_status_index')) {
                $table->dropIndex(['team_id', 'approval_status']);
            }
            if (Schema::hasColumn('contacts', 'phone_digits')) {
                $table->dropColumn('phone_digits');
            }
        });
    }
};
