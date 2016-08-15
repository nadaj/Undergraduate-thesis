<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VotingSuccess extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'voting_success';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function answer()
    {
        return $this->belongsTo('App\Answer', 'foreign_key', 'answer_id');
    }
}
