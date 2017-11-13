<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Get the messages.
     */
    public function user()
    {
        return $this->belongsTo('App\User','sender');
    }


}
