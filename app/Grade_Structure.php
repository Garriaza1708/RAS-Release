<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade_Structure extends Model
{
	protected $table = "grades_structures";
    protected $fillable = ['nombre','grade_id',];

    public function grade()
    {
    	return $this->belongsTo('App\Grade');
    }

    public function grades_values()
    {
    	return $this->hasMany('App\Grade_Value');
    }
}
