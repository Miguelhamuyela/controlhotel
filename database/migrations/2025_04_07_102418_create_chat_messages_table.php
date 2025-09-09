<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatGroupId');
            $table->unsignedBigInteger('senderId');
            // O tipo do sender pode ser 'admin' ou 'employeee'
            $table->enum('senderType', ['admin', 'employeee']);
            $table->string('senderEmail')->nullable();
            $table->text('message');
            $table->timestamps();

            $table->foreign('chatGroupId')->references('id')->on('chat_groups')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}