<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ip_login_logs', function (Blueprint $table) {
            $table->string('session_id', 100)->nullable()->after('user_agent')->index();
            $table->string('browser', 80)->nullable()->after('session_id');
            $table->string('device', 40)->nullable()->after('browser');
            $table->string('platform', 40)->nullable()->after('device');
            $table->timestamp('logout_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('ip_login_logs', function (Blueprint $table) {
            $table->dropColumn(['session_id', 'browser', 'device', 'platform', 'logout_at']);
        });
    }
};
