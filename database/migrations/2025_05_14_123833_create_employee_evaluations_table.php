<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();                                 // id (PK)
            $table->unsignedBigInteger('employeeId');     // funcionário avaliado
            $table->date('evaluationDate');               // data da avaliação
            $table->string('evaluator', 100);             // nome do avaliador
            $table->decimal('overallScore', 4, 2);        // nota média final
            $table->text('comments')->nullable();         // observações/comentários
            $table->timestamps();

            $table->foreign('employeeId')
                  ->references('id')
                  ->on('employeees')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_evaluations');
    }
}
