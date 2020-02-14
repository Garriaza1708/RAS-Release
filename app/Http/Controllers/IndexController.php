<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Period;
class IndexController extends Controller
{

    public function setPeriodo($id)
    {
    	\Session::put('idPeriodo',$id);
        $periodo = Period::select('nombre')->where('id',$id)->first();
        return back()->with('periodo',$periodo->nombre);
    }
}
