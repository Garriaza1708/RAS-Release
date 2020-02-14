<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['estado','lesson_id','student_id',];

    public function lesson()
    {
    	return $this->belongsTo('App\Lesson');
    }

    public function student()
    {
    	return $this->belongsTo('App\Student');
    }
}
