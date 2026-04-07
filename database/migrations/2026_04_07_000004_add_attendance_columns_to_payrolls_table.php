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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->unsignedInteger('present_days')->default(0)->after('staff_employee_id');
            $table->unsignedInteger('late_days')->default(0)->after('present_days');
            $table->unsignedInteger('absent_days')->default(0)->after('late_days');
            $table->unsignedInteger('leave_days')->default(0)->after('absent_days');
            $table->decimal('attendance_deduction', 12, 2)->default(0)->after('other_deduction');
            $table->string('salary_preset_source')->nullable()->after('attendance_deduction');
            $table->string('salary_preset_key')->nullable()->after('salary_preset_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'present_days',
                'late_days',
                'absent_days',
                'leave_days',
                'attendance_deduction',
                'salary_preset_source',
                'salary_preset_key',
            ]);
        });
    }
};
