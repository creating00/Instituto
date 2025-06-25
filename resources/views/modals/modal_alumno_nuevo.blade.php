<div id="nuevo_alumno" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="nuevoAlumnoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-header text-white">
                <h5 class="modal-title" id="nuevoAlumnoLabel">Nuevo Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('alumnos.store') }}" method="POST" autocomplete="off">
                    @csrf

                    @if (session('alert'))
                        <div class="alert alert-info">{{ session('alert') }}</div>
                    @endif

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dni">CÉDULA</label>
                            <input type="number" placeholder="Ingrese CÉDULA" class="form-control" name="dni"
                                id="dni" value="{{ old('dni') }}">
                            @error('dni')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="celular">Celular</label>
                            <input type="text" placeholder="Ingrese celular" class="form-control celular-input"
                                name="celular" id="celular" value="{{ old('celular') }}">
                            @error('celular')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" class="form-control" name="nombre"
                                id="nombre" value="{{ old('nombre') }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="apellido">Apellido</label>
                            <input type="text" placeholder="Ingrese Apellido" class="form-control" name="apellido"
                                id="apellido" value="{{ old('apellido') }}">
                            @error('apellido')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" placeholder="Ingrese Dirección" class="form-control" name="direccion"
                            id="direccion" value="{{ old('direccion') }}">
                        @error('direccion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" placeholder="Ingrese Correo Electrónico" class="form-control"
                            name="email" id="email" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tutor">Tutor</label>
                            <input type="text" placeholder="Ingrese Nombre" class="form-control" name="tutor"
                                id="tutor" value="{{ old('tutor') }}">
                            @error('tutor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contacto">Celular Contacto</label>
                            <input type="text" placeholder="Ingrese Contacto" class="form-control celular-input"
                                name="contacto" id="contacto" value="{{ old('contacto') }}">
                            @error('contacto')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group d-none">
                        <label for="sede">Sede</label>
                        <select name="sede" class="form-control" id="sede">
                            {{-- @foreach ($sedes as $sede)
                                <option value="{{ $sede->nombre }}"
                                    {{ old('sede') == $sede->nombre ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                            @endforeach --}}
                        </select>
                        @error('sede')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Estudiante</button>
                </form>
            </div>
        </div>
    </div>
</div>
