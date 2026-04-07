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
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_employee_id')->constrained('staff_employees')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->string('status', 20);
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('marked_by')->nullable();
            $table->timestamps();

            $table->unique(['staff_employee_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};
