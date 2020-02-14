<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Crear Sesión de Clase</h4>
</div>
<div class="modal-body">
  <div class="box box-primary">
            <div class="box-header with-border"></div>
            <form role="form" id="FormClase" method="POST">
            <input type="hidden" name="SemanaClase" id="SemanaClase">
            <input type="hidden" name="UnidadClase" id="UnidadClase">
              <div class="box-body">
                <div class="form-group">
                  <label for="Nombre">Nombre</label>
                  <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese Nombre">
                </div>
               
              </div>
              
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-save-lesson">Guardar</button>
              </div>
            </form>
  </div>
</div>
<div class="modal-footer">
  <!--<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>-->
  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>

