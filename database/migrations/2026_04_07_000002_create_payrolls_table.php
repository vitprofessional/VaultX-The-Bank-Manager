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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_employee_id')->constrained('staff_employees')->cascadeOnDelete();
            $table->string('payroll_month', 7)->index();
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowance', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('overtime', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('deduction', 12, 2)->default(0);
            $table->decimal('loan', 12, 2)->default(0);
            $table->decimal('other_deduction', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->string('payment_status')->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamps();

            $table->unique(['staff_employee_id', 'payroll_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
