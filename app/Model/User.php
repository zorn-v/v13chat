<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $touches = ['abilities'];

    public function role()
    {
        return $this->belongsTo('App\\Model\\Role');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\\Model\\Ability', 'user_abilities')->withPivot('data');
    }
}
