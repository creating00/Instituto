<div id="modalCurso" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="my-modal-title">Lista de Cursos</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <input type="text" placeholder="Buscar Curso" id="cuadro_buscar_curso" class="form-control" onkeypress="mi_busqueda_curso_mejorado();">
          <div id="mostrar_curso">
            <table id="tablaCurso" class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CURSO</th>
                  <th scope="col">PRECIO</th>
                  <th scope="col">DURACION</th>
                  <th scope="col">SEDE</th>
                  <th scope="col">INSCRIPCION</th>
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

<div id="modalCursoProfesor" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="my-modal-title">Lista de Cursos</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <input type="text" placeholder="Buscar Curso" id="cuadro_buscar_curso_profesor" class="form-control" onkeypress="mi_busqueda_curso_profesor();">
          <div id="mostrar_curso">
            <table id="tablaCurso" class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CURSO</th>
                  <th scope="col">PRECIO</th>
                  <th scope="col">DURACION</th>
                  <th scope="col">SEDE</th>
                  <th scope="col">INSCRIPCION</th>
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