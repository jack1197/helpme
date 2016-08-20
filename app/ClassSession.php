<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'tutor_id', 'id');
    }

    public function studentsession()
    {
        return $this->hasMany('App\StudentSession', 'id', 'class_id');
    }
}
