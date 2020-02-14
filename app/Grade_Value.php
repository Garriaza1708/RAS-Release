<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade_Value extends Model
{
	protected $table = "grade_values";
    protected $fillable = ['grade','grade_structure_id','student_id'];

    public function student()
    {
    	return $this->belongsTo('App\Student');
    }

    public function grade_structure()
    {
    	return $this->belongsTo('App\Grade_Structure');
    }
}
