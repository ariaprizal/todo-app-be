<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_todos', function (Blueprint $table) {
            $table->id('card_todo_id');
            $table->string('card_code');
            $table->unsignedBigInteger("user_id")->index();
            $table->foreign("user_id")->references("user_id")->on("users")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_todos');
    }
}
