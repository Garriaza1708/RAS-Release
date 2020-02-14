<?php

namespace App\Http\Controllers;

use App\Period_Range;
use App\Period;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
class PeriodRangeController extends Controller
{
    public function showPage(){
        return view('menus.ranges');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        return Datatables::of(Period_Range::join('periods as pe','pe.id','=',
            'periods_ranges.period_id')
            ->select('periods_ranges.id as id','pe.id as periodo_id',
                'periods_ranges.nombre as nombre','periods_ranges.duracion as duracion','fecha_inicio','fecha_fin','pe.nombre as periodo')
            ->where('period_id',\Session::get('idPeriodo'))
            ->get())->make(true);
    }

    
    public function setSession(Request $request)
    {
         $request->session()->put('idPeriodRange',$request->id);
         return response()->json(["Sesion"=>"Asignado"]);
    }


    public function save(Request $request)
    {
        if ($request->ajax()) {
            if ($request->Accion == "Registrar") {
                if($this->isLimiteDuracion($request->Duracion,"Guardar"))
                {
                    $per               = new Period_Range;
                    $per->nombre       = $request->Nombre;
                    $per->duracion     = $request->Duracion;
                    $per->fecha_inicio = date('Y-m-d', strtotime(str_replace('/', '-', $request->FechaInicio)));
                    $per->fecha_fin    = date('Y-m-d', strtotime(str_replace('/', '-', $request->FechaFin)));
    
                    $periodo = Period::find(\Session::get('idPeriodo'));
    
                    if($periodo->periods_ranges()->save($per))
                        return response()->json(["Estado"=>"Registrado"]);
                    else
                        return response()->json(["Estado"=>"Error"]);    
                }else{
                    return "Limite";
                }
            }else if($request->Accion == "Editar"){
                if($this->isLimiteDuracion($request->Duracion,"Editar")){
                    $per               = Period_Range::find(\Session::get('idPeriodRange'));
                    $per->nombre       = $request->Nombre;
                    $per->duracion     = $request->Duracion;
                    $per->fecha_inicio = date('Y-m-d', strtotime(str_replace('/', '-', $request->FechaInicio)));
                    $per->fecha_fin    = date('Y-m-d', strtotime(str_replace('/', '-', $request->FechaFin)));

                    $periodo = Period::find(\Session::get('idPeriodo'));

                    if($periodo->periods_ranges()->save($per))
                        return response()->json(["Estado"=>"Editado"]);
                    else
                        return response()->json(["Estado"=>"Error"]);
                }else{
                    return "Limite";
                }
            } 
            $request->session()->forget('idPeriodRange');
        }else
            return response()->json(["Estado"=>"Error"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $per = Period_Range::find($request->id);
        if (!is_null($per)) {
            $per->delete();
            return response()->json(["Estado"=>"Eliminado"]);
        }
            return response()->json(["Estado"=>"Error"]);
    }

    public function isLimiteDuracion($duracionGuardar,$param)
    {
        $duracionTotal = Period::find(\Session::get('idPeriodo'));
        $duracionActual = Period_Range::where('period_id',\Session::get('idPeriodo'))->sum('duracion');
        $diferencia = 0;
        
        if($param == "Editar")
        {
            $duracionRango = Period_Range::find(\Session::get('idPeriodRange'));
            $duracionRango = $duracionActual - $duracionRango->duracion;
            $duracionParcial = $duracionRango + $duracionGuardar;

            $diferencia = $duracionTotal->duracion - $duracionParcial;

        }else
        {
            $diferenciaParcial = $duracionTotal->duracion - $duracionActual;
            $diferencia = $diferenciaParcial - $duracionGuardar;
        }
        
        return $diferencia >= 0;
    }
}
