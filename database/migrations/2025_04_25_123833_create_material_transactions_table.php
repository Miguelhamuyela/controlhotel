<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('material_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('MaterialId');
            $table->enum('TransactionType', ['in','out']);
            $table->date('TransactionDate');
            $table->integer('Quantity');
            $table->string('OriginOrDestination');
            $table->string('DocumentationPath')->nullable();
            $table->text('Notes')->nullable();
        
            // AGORA NULLABLE:
            $table->unsignedBigInteger('DepartmentId')->nullable();
            $table->unsignedBigInteger('CreatedBy')->nullable();
        
            $table->timestamps();
        
            $table->foreign('MaterialId')
                  ->references('id')->on('materials')
                  ->cascadeOnDelete();
            $table->foreign('DepartmentId')
                  ->references('id')->on('departments');
            $table->foreign('CreatedBy')
                  ->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_transactions');
    }
}
