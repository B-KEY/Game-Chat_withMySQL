<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameMove extends Model
{
    //
    public function game()
    {
        return $this->belongsTo('App\Game', game_id);
    }
}
