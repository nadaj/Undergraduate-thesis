<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	// so it doesn't look for the updated_at or created_at
    public $timestamps = false;

    public function answers()
    {
        return $this->belongsToMany('App\Answer');
    }
}	
