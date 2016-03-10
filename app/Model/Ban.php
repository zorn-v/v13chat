<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    public $timestamps = false;
    protected $dates = ['until'];

    const REASON_SILENT = 1;
    const REASON_KICK   = 2;
    const REASON_BAN    = 3;
}
