<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AutenticacionController extends Controller
{
	public function __construct()
	{
		$this->middleware('guest',['except'=>'logout']);
	}

    public function showLoginForm(){
    	return view('login');
    }

    public function login(Request $request)
    {
    	$this->validate($request,[
    		'email' => 'required|email',
    		'password'=> 'required|min:6'
    	]);

    	if (Auth::attempt(['email'=>$request->email,'password'=>$request->password],$request->remember)) {
    		return redirect()->intended(route('app.dashboard'));
    	}

    	return redirect()->back()
            ->withInput($request->only('email','remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        \Session::forget('idPeriodo');
        \Session::forget('idGrade');
        \Session::forget('idGradeSet');
        \Session::forget('idGradeStructure');
        \Session::forget('idGradeSelect');
        \Session::forget('idCourseSelect');
        \Session::forget('idCursoFiltro');
        \Session::forget('idPeriod');
        \Session::forget('idPeriodRange');
        \Session::forget('idAlumno');
        \Session::forget('idUsuario');
        return redirect()->route('app.login.form');
    }
}
