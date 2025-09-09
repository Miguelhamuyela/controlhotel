<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraJobEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('extra_job_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extraJobId');
            $table->unsignedBigInteger('employeeId');
            $table->decimal('bonusAdjustment', 15, 2)->default(0); // Ajuste individual (positivo/negativo)
            $table->decimal('assignedValue', 15, 2)->default(0);   // Valor final atribuído
            $table->timestamps();

            // foreign keys
            $table->foreign('extraJobId')
                  ->references('id')->on('extra_jobs')
                  ->onDelete('cascade');
            $table->foreign('employeeId')
                  ->references('id')->on('employeees')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('extra_job_employees');
    }
}
