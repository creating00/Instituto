<div id="modalCursoProfesor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Lista de Cursos</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <input type="text" placeholder="Buscar Curso" id="cuadro_buscar_curso_profesor"
                        class="form-control mb-2" onkeypress="mi_busqueda_curso_profesor();">
                    <div id="mostrar_curso_profesor">
                        <table id="tablaCursoProfesor" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CURSO</th>
                                    <th>PRECIO</th>
                                    <th>DURACIÓN</th>
                                    <th>SEDE</th>
                                    <th>INSCRIPCIÓN</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cursosProfesor as $curso)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $curso->nombre }}</td>
                                        <td>{{ number_format($curso->precio, 2) }}</td>
                                        <td>{{ $curso->duracion }}</td>
                                        <td>{{ $curso->sede->nombre ?? 'N/A' }}</td>
                                        <td>{{ $curso->inscripcion ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm seleccionar-curso-profesor"
                                                data-id="{{ $curso->id }}" data-nombre="{{ $curso->nombre }}">
                                                Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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