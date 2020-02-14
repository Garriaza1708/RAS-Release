<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nombre','apellidos','direccion','email'];
    
    public function courses()
    {
    	return $this->belongsToMany('App\Course','courses_students')
    	             ->withTimestamps();
    }

    public function attendances()
    {
    	return $this->hasMany('App\Attendance');
    }

    public function grades_values()
    {
        return $this->hasMany('App\Grade_Value');
    }
}
