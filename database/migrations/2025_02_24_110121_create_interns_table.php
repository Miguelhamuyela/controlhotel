<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternsTable extends Migration
{
    public function up()
    {
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departmentId');
            $table->string('fullName');
            $table->string('address');
            $table->string('mobile');
            $table->string('fatherName');
            $table->string('motherName');
            $table->string('bi')->unique();
            $table->date('birth_date');
            $table->string('nationality');
            $table->enum('gender', ['Masculino', 'Feminino']);
            $table->string('email')->unique();
            $table->unsignedBigInteger('positionId');
            $table->unsignedBigInteger('specialtyId');
            $table->date('internshipStart');
            $table->date('internshipEnd');
            $table->string('institution');
            $table->timestamps();

            $table->foreign('departmentId')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('positionId')->references('id')->on('positions');
            $table->foreign('specialtyId')->references('id')->on('specialties');
        });
    }

    public function down()
    {
        Schema::dropIfExists('interns');
    }
}
