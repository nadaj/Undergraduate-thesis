<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function department()
    {
    	return $this->belongsTo('App\Department');
    }

    public function title()
    {
    	return $this->belongsTo('App\Title');
    }

    public function role()
    {
    	return $this->belongsTo('App\Role');
    }

    public function hasRole($role)
    {
        if ($this->role_id === intval($role)) {
            return true;
        }
        return false;
    }
}
