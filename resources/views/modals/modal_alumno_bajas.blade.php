<!-- Modal Alumnos dados de baja -->
<div class="modal fade" tabindex="-1" id="bajas" aria-hidden="true" aria-labelledby="bajasLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-plus" aria-hidden="true"></i> Estudiantes dados de Bajas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Listado Completo.</p>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <!--<th>#</th>-->
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Dirección</th>
                                <th>Celular</th>
                                <th>Email</th>
                                <th>Tutor</th>
                                <th>Contacto</th>
                                <th hidden>Sede</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alumnosInactivos as $alumno)
                            <tr>
                                {{-- <td>{{ $alumno->idalumno }}</td> --}}
                                <td>{{ $alumno->dni }}</td>
                                <td>{{ $alumno->nombre }}</td>
                                <td>{{ $alumno->apellido }}</td>
                                <td>{{ $alumno->direccion }}</td>
                                <td>{{ $alumno->celular }}</td>
                                <td>{{ $alumno->email }}</td>
                                <td>{{ $alumno->tutor }}</td>
                                <td>{{ $alumno->contacto }}</td>
                                <td hidden>{{ $alumno->sede }}</td>
                                <td>{{ $alumno->estado }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('alumnos.alta', $alumno->idalumno) }}" class="btn btn-warning mr-2" title="Dar de alta">
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('alumnos.destroy', $alumno->idalumno) }}" method="POST" class="confirmar">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No hay estudiantes dados de baja.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
