<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_moves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id');
            $table->integer('whoseTurn');
            $table->string('player0_id');
            $table->string('player0_pieceId');
            $table->integer('player0_diceValue');
            $table->string('player0_fromPosition');
            $table->string('player0_toPosition');
            $table->string('player0_moveType');
            $table->string('player0_score');
            $table->string('player0_difference');
            $table->string('player0_rolled');

            $table->string('player1_id');
            $table->string('player1_pieceId');
            $table->integer('player1_diceValue');
            $table->string('player1_fromPosition');
            $table->string('player1_toPosition');
            $table->string('player1_moveType');
            $table->string('player1_score');
            $table->string('player1_difference');
            $table->string('player1_rolled');

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
        Schema::dropIfExists('game_moves');
    }
}
