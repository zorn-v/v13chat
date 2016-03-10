<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function user_from()
    {
        return $this->belongsTo('App\\Model\\User', 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo('App\\Model\\User', 'user_to');
    }
}
