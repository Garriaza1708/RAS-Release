<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['nombre','fecha','hora_inicio','semana',
            'period_range_id','course_id',];

    public function course()
    {
    	return $this->belongsTo('App\Course');
    }

    public function period_range()
    {
    	return $this->belongsTo('App\Period_Range');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }
}
