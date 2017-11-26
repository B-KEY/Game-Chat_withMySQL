<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    //
    public function move()
    {
        return $this->hasOne('App\GameMove');
    }
    public function challenge()
    {
        return $this->hasOne('App\Challenge');
    }

}
