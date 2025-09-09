<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirementsTable extends Migration
{
    public function up()
    {
        Schema::create('retirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->date('requestDate')->default(now());
            $table->date('retirementDate')->nullable();
            $table->string('status')->default('Pendente');
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->foreign('employeeId')
                  ->references('id')
                  ->on('employeees')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('retirements');
    }
}
