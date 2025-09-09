<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraJobsTable extends Migration
{
    public function up()
    {
        Schema::create('extra_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');                     
            $table->decimal('totalValue', 15, 2)->default(0);
            $table->enum("status", ["Pending", "Approved", "Rejected"])->default("Pending");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('extra_jobs');
    }
}
