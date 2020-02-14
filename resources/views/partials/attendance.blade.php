<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Asistencia</h4>
</div>
<div class="modal-body modal-body-attendance">
            
                <div class="table-responsive" id="ListaAlumnosxCurso">
                  <!--Dinamicamente-->
                  
                  @if(count($students))
                  <form role="form" method="POST" id="FormAsistencias">
                  <input type="hidden" name="Clase" id="Clase">
                  <table class="table table-striped table-bordered table-hover table-condensed" id="TblAsistencias">
                    <thead>
                      <tr>
                        <th height="45">NOMBRES Y APELLIDOS</th>
                        <th>ASISTENCIA</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($students as $student)
                        <tr>
                          <td style="font-size: 16px;">
                              <input type="hidden" name="Alumnos" id="Alumnos"
                              value="{{ $student->id }}">
                                {{ $student->alumno }}
                          </td>
                          <td>
                            <select class="form-control al{{ $student->id }}" id="Asistencias"
                            name="Asistencias">
                              <option value="Asistencia">Asistencia</option>
                              <option value="Tardanza">Tardanza</option>
                              <option value="Falta">Falta</option>
                            </select>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  </form>
                  @else
                  <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" 
                      aria-hidden="true">×</button>
                      <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
                      No existe alumnos registrados en el curso
                  </div>
                  @endif

                </div>
               
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" id="btnSaveAttendance" 
                  class="btn btn-primary">
                    Guardar
                </button>
              </div>

</div>
<div class="modal-footer">
  <div id="ImgLoad">
    
  </div>

    <span style="color:green;font-size:17px;float:left;font-weight:bold;display:none;" 
    id="MsjOK">
      <i class="icon fa fa-check"></i> 
      Asistencias Registradas Correctamente
    </span>
    <span style="color:red;font-size:17px;float:left;font-weight:bold;display:none;" 
    id="MsjError">
      <i class="icon fa fa-ban"></i> 
      Ocurrio un error. Intentelo de nuevo.
    </span>
  <button type="button" class="btn btn-default btn-close-update" data-dismiss="modal">Cerrar</button>
</div>

