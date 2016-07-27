<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = ['name', 'description', 'from', 'to', 'multiple_answers','status', 'initiator_id'];
}
