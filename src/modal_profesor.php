<div id="modalProfesor" class="modal fade" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="my-modal-title">Lista de Profesores</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <input type="text" placeholder="Buscar Profesor" id="cuadro_buscar_profesor" class="form-control" onkeypress="mi_busqueda_profesor_mejorado();">
          <div id="mostrar_profesor">
            <table id="tablaProfesor" class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CÉDULA</th>
                  <th scope="col">NOMBRE</th>
                  <th scope="col">APELLIDO</th>
                  <th scope="col">SEDE</th>
                  <th scope="col">ACCION</th>
                </tr>
              </thead>
              <tbody>
                <!-- Filas dinámicas -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Estilo CSS específico para el modal -->
<!--<style>
  /* Fondo del modal */
  .modal-backdrop {
    z-index: 1030 !important;
    /* Se asegura que el fondo esté debajo del modal */
  }

  #nuevo_curso {
    z-index: 1040 !important;
    /* Modal de curso debajo */
  }

  #modalProfesor {
    z-index: 1050 !important;
    /* Modal de profesor encima */
  }
</style>-->