<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employeeId');
            $table->unsignedBigInteger('departmentId');
            $table->unsignedBigInteger('leaveTypeId');
            $table->date('leaveStart');
            $table->date('leaveEnd');
            $table->text('reason')->nullable();
            $table->string('approvalStatus')->default('Pendente');
            $table->string('approvalComment')->nullable();
            $table->timestamps();
            $table->foreign('employeeId')->references('id')->on('employeees')->onDelete('cascade');
            $table->foreign('departmentId')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('leaveTypeId')->references('id')->on('leave_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
