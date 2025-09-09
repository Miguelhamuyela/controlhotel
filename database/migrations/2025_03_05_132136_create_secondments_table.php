<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondmentsTable extends Migration
{
    public function up()
    {
        Schema::create('secondments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->string('causeOfTransfer')->nullable();
            $table->string('institution');
            $table->string('supportDocument')->nullable();
            $table->string('originalFileName')->nullable();
            $table->timestamps();

            $table->foreign('employeeId')->references('id')->on('employeees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('secondments');
    }
}
