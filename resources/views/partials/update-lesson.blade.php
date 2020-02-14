<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Actualizar Sesión de Clase</h4>
</div>
<div class="modal-body">
  <div class="box box-primary">
            <div class="box-header with-border"></div>
            <form role="form" id="FormClaseUpdate" method="POST">
              <div class="box-body">
              <input type="hidden" id="Actualizado" name="Actualizado">
              <input type="hidden" name="SemanaClaseActualizado" id="SemanaClaseActualizado">
              <input type="hidden" name="UnidadClaseActualizado" id="UnidadClaseActualizado">
                <div class="form-group">
                  <label for="NombreNuevo">Nombre</label>
                  <input type="text" class="form-control" id="NombreNuevo" name="NombreNuevo" placeholder="Ingrese el nuevo nombre">
                </div>
               
              </div>
              
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" class="btn btn-primary btn-update-lesson">Guardar</button>
              </div>
            </form>
  </div>
</div>
<div class="modal-footer">
  <!--<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>-->
  <button type="button" class="btn btn-default btn-close-update" data-dismiss="modal">Cerrar</button>
</div>

