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
            $table->string('player1_id');
            $table->string('player1piece_id');
            $table->integer('player1dice_value');
            $table->string('player1from_position');
            $table->string('player1to_position');
            $table->string('player1move_type');
            $table->string('player2_id');
            $table->string('player2piece_id');
            $table->integer('playee2dice_value');
            $table->string('player2from_position');
            $table->string('player2to_position');
            $table->string('player2move_type');
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
