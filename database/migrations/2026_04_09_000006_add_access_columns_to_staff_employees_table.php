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
        Schema::table('staff_employees', function (Blueprint $table) {
            $table->boolean('can_login')->default(false)->after('avatar_path');
            $table->boolean('attendance_access')->default(false)->after('can_login');
            $table->string('login_password')->nullable()->after('attendance_access');
            $table->string('device_fingerprint', 191)->nullable()->unique()->after('login_password');
            $table->timestamp('device_bound_at')->nullable()->after('device_fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_employees', function (Blueprint $table) {
            $table->dropColumn([
                'can_login',
                'attendance_access',
                'login_password',
                'device_fingerprint',
                'device_bound_at',
            ]);
        });
    }
};
