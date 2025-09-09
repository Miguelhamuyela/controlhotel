<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleDriversTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_driver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicleId');
            $table->unsignedBigInteger('driverId');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->timestamps();

            $table->foreign('vehicleId')
                  ->references('id')->on('vehicles')
                  ->onDelete('cascade');

            $table->foreign('driverId')
                  ->references('id')->on('drivers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_driver');
    }
}
