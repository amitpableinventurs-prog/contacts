<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// The suspend/ban (blacklist) feature writes these columns. They exist on the
// legacy production database but were never in the migrations, so fresh
// installs (and the test schema) were missing them.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('contacts', 'status')) {
                $table->string('status', 20)->nullable()->index()->after('rating');
            }
            if (! Schema::hasColumn('contacts', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('contacts', 'banned_at')) {
                $table->timestamp('banned_at')->nullable()->after('suspended_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            foreach (['banned_at', 'suspended_at', 'status'] as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
