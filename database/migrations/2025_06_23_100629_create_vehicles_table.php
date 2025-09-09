<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate', 12)->unique();
            $table->string('model', 50);
            $table->string('brand', 50);
            $table->integer('yearManufacture');
            $table->string('color', 30);
            $table->decimal('loadCapacity', 10, 2);
            $table->decimal('totalMileage', 12, 2)->default(0);
            $table->date('lastMaintenanceDate')->nullable();
            $table->enum('status', ['Available', 'UnderMaintenance', 'Unavailable'])->default('Available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
