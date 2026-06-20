<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_team_id')->nullable()->after('email')->constrained('teams')->nullOnDelete();
            $table->string('photo')->nullable()->after('current_team_id');
            $table->string('phone')->nullable()->after('photo');
            $table->string('timezone')->default('UTC')->after('phone');
            $table->string('locale', 8)->default('en')->after('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_team_id');
            $table->dropColumn(['photo', 'phone', 'timezone', 'locale']);
        });
    }
};
