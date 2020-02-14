<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','DashboardController@index')->name('app.dashboard');
//index
//Route::get('/app/periodos','IndexController@getPeriodos')->name('app.get.periodos');

Route::get('setperiodo/{id}','IndexController@setPeriodo')->name('app.set.periodo');
//Auth::routes();
Route::get('login','Auth\AutenticacionController@showLoginForm')->name('app.login.form');
Route::post('login','Auth\AutenticacionController@login')->name('app.login.submit');
Route::post('logout','Auth\AutenticacionController@logout')->name('app.logout');

//Periodos
Route::get('periodo','PeriodController@showPage')->name('app.period.page');
Route::get('periodo/listar','PeriodController@listAll')->name('app.period.list');
Route::post('periodo/insertar','PeriodController@save')->name('app.period.save');
Route::post('periodo/sesion','PeriodController@setSession')->name('app.period.sesion');
Route::post('periodo/eliminar','PeriodController@delete')->name('app.period.delete');
//Rangos
Route::get('rango','PeriodRangeController@showPage')->name('app.range.page');
Route::get('rango/listar','PeriodRangeController@listAll')->name('app.range.list');
Route::post('rango/insertar','PeriodRangeController@save')->name('app.range.save');
Route::post('rango/sesion','PeriodRangeController@setSession')->name('app.range.sesion');
Route::post('rango/eliminar','PeriodRangeController@delete')->name('app.range.delete');
//Cursos
Route::get('curso','CourseController@showPage')->name('app.course.page');
Route::get('curso/listar','CourseController@listAll')->name('app.course.list');
Route::post('curso/insertar','CourseController@save')->name('app.course.save');
Route::post('curso/sesion','CourseController@setSession')->name('app.course.sesion');
Route::post('curso/eliminar','CourseController@delete')->name('app.course.delete');

//clases
Route::get('clases','LessonController@showPage')->name('app.class.page');
Route::post('clase/insertar','LessonController@save')->name('app.lesson.save');
Route::post('clase/actualizar','LessonController@update')->name('app.lesson.update');
Route::get('clase/get/{id}','LessonController@getLessonById')->name('app.lesson.get');
Route::get('clase/eliminar/{id}','LessonController@delete')->name('app.lesson.delete');
Route::post('clase/set/curso/{id}','LessonController@setCurso')
     ->name('app.lesson.set.curso');

//asistencias
Route::post('asistencia/insertar','AttendanceController@save')
     ->name('app.attendance.save');
Route::post('asistencia/actualizar','AttendanceController@update')
     ->name('app.attendance.update');
Route::get('asistencia/get/{id}','AttendanceController@getAttendanceLesson')
	->name('app.attendance.lesson.get');

//notas-configuracion
Route::get('nota','GradeController@showPage')->name('app.grade.page');
Route::get('nota/listar','GradeController@listAll')->name('app.grade.list');
Route::post('nota/insertar','GradeController@save')->name('app.grade.save');
Route::post('nota/sesion','GradeController@setSession')->name('app.grade.sesion');
Route::post('nota/eliminar','GradeController@delete')->name('app.grade.delete');

//notas-estructura
Route::get('notaestructura','GradeStructureController@showPage')->name('app.gradestructure.page');
Route::get('notaestructura/listar','GradeStructureController@listAll')->name('app.gradestructure.list');
Route::post('notaestructura/insertar','GradeStructureController@save')->name('app.gradestructure.save');
Route::post('notaestructura/sesion','GradeStructureController@setSession')->name('app.gradestructure.sesion');
Route::post('notaestructura/sesion/structure','GradeStructureController@setSessionStructure')->name('app.gradestructure.sesion.structure');
Route::post('notaestructura/eliminar','GradeStructureController@delete')->name('app.gradestructure.delete');

//notas-ingreso
Route::get('notaingreso','GradeValueController@showPage')
      ->name('app.grade.ingreso.page');
Route::get('notaingreso/list/{id}','GradeValueController@getGradesByCourse')
      ->name('app.grade.course.list');
Route::get('notaingreso/students/{idCourse}/{idSubGrade}',
	'GradeValueController@getStudents')->name('app.grade.get.students');
Route::post('notaingreso/insertar','GradeValueController@save')
    ->name('app.grade.value.save');
Route::get('notaingreso/get/values/{id}/{idper}',
	'GradeValueController@getGradeStudents')->name('app.grade.get.values');
//Alumnos
Route::get('alumno','StudentController@showPage')->name('app.student.page');
Route::get('alumno/listar','StudentController@listAll')->name('app.student.list');
Route::post('alumno/insertar','StudentController@save')->name('app.student.save');
Route::post('alumno/sesion','StudentController@setSession')->name('app.student.sesion');
Route::post('alumno/eliminar','StudentController@delete')->name('app.student.delete');
Route::post('alumno/asignar','StudentController@asignarCursos')->name('app.student.cursos');
Route::get('alumnos','StudentController@getAlumnos')->name('app.students.get');
//Usuarios
Route::get('usuario','UserController@showPage')->name('app.user.page');
Route::get('usuario/listar','UserController@listAll')->name('app.user.list');
Route::post('usuario/insertar','UserController@save')->name('app.user.save');
Route::post('usuario/sesion','UserController@setSession')->name('app.user.sesion');
Route::post('usuario/eliminar','UserController@delete')->name('app.user.delete');
Route::post('usuario/foto','UserController@subirFoto')->name('app.user.foto');

//Reportes
Route::get('reportes','ReportController@showPage')->name('app.report.page');
//Route::post('test','ReportController@test')->name('app.test');
Route::get('reportesdetalles/{par}','ReportController@showDetailPage')
    ->name('app.report.detail.page');
Route::get('reportes/prom/{id}','ReportController@promedioGenerales')
    ->name('app.report.prom');
Route::get('reportes/detallenotas/{id}','ReportController@detalleNotas')
    ->name('app.report.detallenotas');
Route::get('reportes/alaprodes/{id}','ReportController@estadosNotasAlumnos')
    ->name('app.report.alaprodes');
Route::get('reportes/alasis/{id}','ReportController@asistenciasAlumnos')
    ->name('app.report.alasis');
Route::post('reportes/asis/detalle','ReportController@getDetalleAsistencias')
    ->name('app.report.details.attendance');

//excel
Route::get('descargar','ExcelController@descargarPlantilla')
    ->name('app.excel.download');

Route::post('subirexcel','ExcelController@subirExcel')
    ->name('app.excel.upload');

//dashboard
Route::get('dashboard/test','DashboardController@getTableroTres')
    ->name('app.test');

Route::post('dashboard/sendemail','MailController@enviarMail')
    ->name('app.send.email');

Route::post('dashboard/tablerodos','DashboardController@getTableroDos')
    ->name('app.get.tablero.dos');

Route::get('dashboard/tablerotres','DashboardController@getTableroTres')
    ->name('app.get.tablero.tres');

Route::post('dashboard/tablerocuatro','DashboardController@getTableroCuatro')
    ->name('app.get.tablero.cuatro');

Route::post('dashboard/tablerocinco','DashboardController@getTableroCinco')
    ->name('app.get.tablero.cinco');

Route::post('dashboard/students','DashboardController@getAlumnosCurso')
    ->name('app.get.students.course');

Route::post('dashboard/grafico','DashboardController@configurarGrafico')
    ->name('app.get.tablero.grafico');

//generar PDF
Route::get('generarpdf/{id}/{tipo}','ReportController@generarPDF')
    ->name('app.generar.pdf');


