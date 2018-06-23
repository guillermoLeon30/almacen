<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        <h4 class="modal-title">Nuevo Contacto</h4>

      </div>
        <form id="formNuevo" class="form-horizontal">
          <div class="modal-body">
            <div class="form-group col-sm-12">
              <label class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">
                <input type="text" name="nombre" class="form-control">
                <input type="hidden" name="proveedor_id" value="{{ $proveedor_id }}">
              </div>
            </div>

            <div class="form-group col-sm-12">
              <label class="col-sm-2 control-label">Telefono</label>
              <div class="col-sm-10">
                <input type="text" name="telefono" class="form-control">
              </div>
            </div>

            <div class="form-group col-sm-12">
              <label class="col-sm-2 control-label">Celular</label>
              <div class="col-sm-10">
                <input type="text" name="celular" class="form-control">
              </div>
            </div>

            <div class="form-group col-sm-12">
              <label class="col-sm-2 control-label">Correo</label>
              <div class="col-sm-10">
                <input type="text" name="correo" class="form-control">
              </div>
            </div>

            <div class="form-group col-sm-12">
              <label class="col-sm-2 control-label">Cargo</label>
              <div class="col-sm-10">
                <input type="text" name="cargo" class="form-control">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
        
    </div>
  </div>
</div>