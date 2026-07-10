<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // ISO2 country for the phone number's dial code (e.g. "in").
            // The phone column itself keeps the national number so search,
            // imports and duplicate checks keep matching existing data.
            $table->string('phone_country', 2)->nullable()->after('phone_digits');
            // Comment visible to every role but editable only by Super Admin.
            $table->text('admin_comment')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['phone_country', 'admin_comment']);
        });
    }
};
