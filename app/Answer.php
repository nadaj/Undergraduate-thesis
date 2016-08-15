<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['answer'];

    public function tickets()
    {
        return $this->belongsToMany('App\Ticket');
    }

    public function success()
    {
        return $this->hasOne('App\VotingSuccess');
    }
}
