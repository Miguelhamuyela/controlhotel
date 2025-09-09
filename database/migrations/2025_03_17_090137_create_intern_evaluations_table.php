<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('intern_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internId');
            $table->string('evaluationStatus')->default('Pendente');
            $table->text('evaluationComment')->nullable();

            $table->string('pontualidade')->nullable();
            $table->string('trabalhoEquipe')->nullable();
            $table->string('autodidacta')->nullable();
            $table->string('disciplina')->nullable();
            $table->string('focoResultado')->nullable();
            $table->string('comunicacao')->nullable();
            $table->string('apresentacao')->nullable();

            // Novos campos
            $table->text('programaEstagio')->nullable();
            $table->text('projectos')->nullable();
            $table->text('atividadesDesenvolvidas')->nullable();

            $table->timestamps();

            $table->foreign('internId')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_evaluations');
    }
}
