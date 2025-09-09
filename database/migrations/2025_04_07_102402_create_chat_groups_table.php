<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('chat_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            // As opções incluem: directorGroup, departmentGroup, departmentHeadsGroup e individual.
            $table->enum('groupType', ['directorGroup', 'departmentGroup', 'departmentHeadsGroup', 'individual']);
            $table->unsignedBigInteger('departmentId')->nullable();
            $table->unsignedBigInteger('headId')->nullable();
            // Nova coluna para identificar de forma única as conversas individuais.
            $table->string('conversation_key')->nullable();
            $table->timestamps();
            
            // Se desejar relacionar com a tabela departments:
            // $table->foreign('departmentId')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_groups');
    }
}