<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'tutor_id', 'id');
    }
}
