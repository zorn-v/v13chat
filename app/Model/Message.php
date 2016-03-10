<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function userFrom()
    {
        return $this->belongsTo('App\\Model\\User');
    }

    public function userTo()
    {
        return $this->belongsTo('App\\Model\\User', 'user_to');
    }
}
