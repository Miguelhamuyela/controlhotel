<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->date('workMonth');
            $table->decimal('baseSalary', 15, 2)->default(0);
            $table->decimal('subsidies', 15, 2)->default(0);
            $table->decimal('irtRate', 6, 2)->default(0);
            $table->decimal('inssRate', 5, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('salaryAmount', 15, 2);

            $table->date('paymentDate')->nullable();
            $table->string('paymentStatus')->default('Pending');
            $table->text('paymentComment')->nullable();

            $table->timestamps();

            $table->foreign('employeeId')
                  ->references('id')
                  ->on('employeees')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_payments');
    }
}