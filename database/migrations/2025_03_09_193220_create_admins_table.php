<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId')->nullable();
            $table->string('role')->default('admin');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('directorType')->nullable();
            $table->string('directorName')->nullable();
            $table->string('directorPhoto')->nullable();
            $table->string('photo')->nullable();
            $table->text('biography')->nullable();    
            $table->string('linkedin')->nullable();     
            $table->unsignedBigInteger('department_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('employeeId')->references('id')->on('employeees')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
