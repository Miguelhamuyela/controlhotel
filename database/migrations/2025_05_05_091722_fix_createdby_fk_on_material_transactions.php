<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCreatedbyFkOnMaterialTransactions extends Migration
{
    public function up()
    {
        Schema::table('material_transactions', function (Blueprint $table) {
            // 1) Remove a constraint antiga que apontava para users.id
            $table->dropForeign(['CreatedBy']);
            // 2) Cria a nova constraint apontando para employeees.id
            $table->foreign('CreatedBy')
                  ->references('id')
                  ->on('employeees')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('material_transactions', function (Blueprint $table) {
            // Reverte: remove FK para employeees e recria a original para users
            $table->dropForeign(['CreatedBy']);
            $table->foreign('CreatedBy')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
}
