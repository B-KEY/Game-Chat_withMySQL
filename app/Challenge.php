<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    //

    public function haveChallenged(){
        return $this->belongsTo('App\User','sender');
    }
    public function getChallenged(){
        return $this->belongsTo('App\User','receiver');
    }

    public function game()
    {
        return $this->belongsTo('App\Game', 'game_id');
    }

}
