<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    private $userAbils = null;

    public function role()
    {
        return $this->belongsTo('App\\Model\\Role');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\\Model\\Ability', 'user_abilities')->withPivot('data');
    }

    public function getAbility($userAbil)
    {
        if ($this->userAbils === null) {
            foreach ($this->abilities as $ability) {
                $this->userAbils[$ability->name] = $ability->pivot->data;
            }
        }
        return isset($this->userAbils[$userAbil]) ? $this->userAbils[$userAbil] : null;
    }
}
