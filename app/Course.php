<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['nombre','descripcion','user_id','period_id',];

    public function period()
    {
    	return $this->belongsTo('App\Period');
    }

    public function students()
    {
    	return $this->belongsToMany('App\Student','courses_students')
                    ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany('App\Grade');
    }

    public function lessons()
    {
        return $this->hasMany('App\Lesson');
    }
}
