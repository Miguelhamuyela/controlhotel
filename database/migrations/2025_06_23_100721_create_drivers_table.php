<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId')->nullable();
            $table->string('fullName')->nullable();
            $table->string('bi', 16)->nullable()->unique();
            $table->string('licenseNumber', 20)->nullable()->unique();
             $table->unsignedBigInteger('licenseCategoryId');
            $table->date('licenseExpiry');
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('employeeId')->references('id')->on('employeees')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
