<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilitiesTable extends Migration
{
    public function up()
    {
        Schema::create('mobilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->unsignedBigInteger('oldDepartmentId')->nullable();
            $table->unsignedBigInteger('newDepartmentId');
            $table->text('causeOfMobility')->nullable();
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('employeeId')
                  ->references('id')
                  ->on('employeees')
                  ->onDelete('cascade');

            $table->foreign('oldDepartmentId')
                  ->references('id')
                  ->on('departments')
                  ->nullOnDelete();

            $table->foreign('newDepartmentId')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobilities');
    }
}
