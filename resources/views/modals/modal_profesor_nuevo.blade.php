<div id="nuevo_profesor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="nuevoProfesorLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <form action="{{ route('profesores.store') }}" method="POST" autocomplete="off" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="nuevoProfesorLabel">Nuevo Profesor</h5>
                <button class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session('alert'))
                    <div class="alert alert-info">{{ session('alert') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dni">CÉDULA</label>
                            <input type="number" class="form-control" name="dni" id="dni"
                                placeholder="Ingrese CÉDULA">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                placeholder="Ingrese Nombre">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellido"
                                placeholder="Ingrese Apellido">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Ingrese Correo">
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" name="celular" id="celular"
                                placeholder="Ingrese Celular">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" name="direccion" id="direccion"
                                placeholder="Ingrese Dirección">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="idsede">Sede</label>
                            <select name="idsede" id="idsede" class="form-control">
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->idsede }}">{{ $sede->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Profesor</button>
            </div>
        </form>
    </div>
</div>