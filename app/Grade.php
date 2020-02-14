<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['nombre','descripcion','course_id','isConfigurable',];

    public function grades_structures()
    {
    	return $this->hasMany('App\Grade_Structure');
    }

    public function course()
    {
    	return $this->belongsTo('App\Course');
    }

}
