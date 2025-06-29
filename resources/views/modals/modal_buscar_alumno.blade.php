<div id="alumnoList" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Lista de Estudiantes</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive-lg">

                    <div id="mostrar_mensaje" class="mt-3"></div>

                    <table id="tablaAlumno" class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">CÉDULA</th>
                                <th scope="col">NOMBRE</th>
                                <th scope="col">APELLIDO</th>
                            </tr>
                        </thead>
                        <tbody id="tablaAlumnoBody">
                            {{-- @foreach ($alumnos as $alumno)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $alumno->cedula }}</td>
                                    <td>{{ $alumno->nombre }}</td>
                                    <td>{{ $alumno->apellido }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
