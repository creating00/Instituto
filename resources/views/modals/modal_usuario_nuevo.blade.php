<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="nuevoUsuarioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('usuarios.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="nuevoUsuarioLabel">Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Errores --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-row"> {{-- fila para inputs dos columnas --}}
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                value="{{ old('nombre') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" name="correo" id="correo"
                                value="{{ old('correo') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usuario">Usuario</label>
                            <input type="text" class="form-control" name="usuario" id="usuario"
                                value="{{ old('usuario') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="idrol">Rol</label>
                            <select name="idrol" id="idrol" class="form-control" required>
                                {{-- Opciones rol aquí --}}
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="clave">Contraseña</label>
                            <input type="password" class="form-control" name="clave" id="clave" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="clave_confirmation">Confirmar Contraseña</label>
                            <input type="password" class="form-control" name="clave_confirmation"
                                id="clave_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="idsede">Sede</label>
                        <select name="idsede" id="idsede" class="form-control" required>
                            {{-- Opciones sede aquí --}}
                        </select>
                    </div>

                    <input type="hidden" name="estado" value="1">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
