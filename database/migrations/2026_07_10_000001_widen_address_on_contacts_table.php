<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // The form validates address up to 500 chars but the column was
    // varchar(191) — long addresses crashed on save in strict mode.
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
        });
    }
};
