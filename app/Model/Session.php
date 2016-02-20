<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'sess_id';
    protected $hidden = ['sess_data'];
    
    public function user()
    {
        return $this->belongsTo('App\\Model\\User');
    }
}
