<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTypesTable extends Migration
{
    public function up()
    {
        Schema::create('material_types', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'INFRAESTRUTURA' OU 'SERVIÇOS_GERAIS'
            $table->string('name'); 
            $table->unique(['category','name']);
            $table->text('description')->nullable();
            $table->timestamps();

           
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_types');
    }
}
