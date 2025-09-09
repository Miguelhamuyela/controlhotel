<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->date('recordDate'); // Data do registro
            $table->string('status'); //Presente, Ausente, Férias, Licença, Doença, Teletrabalho, etc.
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->foreign('employeeId')->references('id')->on('employeees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}
