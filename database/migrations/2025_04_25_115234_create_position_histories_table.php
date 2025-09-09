<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('position_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->unsignedBigInteger('positionId');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->timestamps();

            $table->foreign('employeeId')->references('id')->on('employeees')->onDelete('cascade');
            $table->foreign('positionId')->references('id')->on('positions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('position_histories');
    }
}