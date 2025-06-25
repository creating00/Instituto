<div id="nuevo_curso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-header text-white">
                <h5 class="modal-title" id="my-modal-title">Registrar Nuevo Curso</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('cursos.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="action" value="registrarCurso">

                    @if (session('alert'))
                        <div class="alert alert-info">{{ session('alert') }}</div>
                    @endif

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Curso</label>
                            <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre"
                                id="nombre" value="{{ old('nombre') }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="precio">Precio $</label>
                            <input type="number" class="form-control" placeholder="Ingrese el Precio" name="precio"
                                id="precio" value="{{ old('precio') }}">
                            @error('precio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fechaComienzo">Fecha de Inicio</label>
                            <input type="date" id="fechaComienzo" name="comienzo" class="form-control"
                                onchange="calcularFechaTermino()" value="{{ old('comienzo') }}">
                            @error('comienzo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="duracion">Duración</label>
                            <select name="duracion" class="form-control" id="duracion"
                                onchange="calcularFechaTermino()">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('duracion') == $i ? 'selected' : '' }}>
                                        {{ $i }} Mes{{ $i > 1 ? 'es' : '' }}</option>
                                @endfor
                            </select>
                            @error('duracion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fechaTermino">Fecha de Término</label>
                        <input type="date" id="fechaTermino" name="termino" class="form-control"
                            value="{{ old('termino') }}">
                        @error('termino')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group d-none">
                        <label for="sede">Sede</label>
                        <select name="sedes" class="form-control" id="sede">
                            {{-- This section would dynamically load sedes from your Laravel backend --}}
                            {{-- Example (assuming $sedes is passed from controller): --}}
                            {{-- @foreach ($sedes as $sedeOption)
                                <option value="{{ $sedeOption->nombre }}" {{ old('sedes') == $sedeOption->nombre ? 'selected' : '' }}>
                                    {{ $sedeOption->nombre }}
                                </option>
                            @endforeach --}}
                        </select>
                        @error('sedes')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dias">Día de Pago (opcional)</label>
                            <input type="number" class="form-control" placeholder="Ingrese los días" name="dias"
                                id="dias" value="{{ old('dias') }}">
                            @error('dias')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="diasRecordatorio">Día de Recordatorio (opcional)</label>
                            <input type="number" class="form-control" placeholder="Ingrese los días de recordatorio"
                                name="diasRecordatorio" id="diasRecordatorio" value="{{ old('diasRecordatorio') }}">
                            @error('diasRecordatorio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mora">Mora $</label>
                        <input type="number" class="form-control" placeholder="Ingrese el monto de mora" name="mora"
                            id="mora" value="{{ old('mora') }}">
                        @error('mora')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="horarioDesde">Horario (Desde)</label>
                            <input type="time" id="horarioDesde" name="horarioDesde" class="form-control"
                                value="{{ old('horarioDesde') }}">
                            @error('horarioDesde')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="horarioHasta">Horario (Hasta)</label>
                            <input type="time" id="horarioHasta" name="horarioHasta" class="form-control"
                                value="{{ old('horarioHasta') }}">
                            @error('horarioHasta')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inscripcion">Costo de Inscripción $</label>
                        <input type="number" class="form-control" placeholder="Ingrese el costo de inscripción"
                            name="inscripcion" id="inscripcion" value="{{ old('inscripcion') }}">
                        @error('inscripcion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#modalProfesor">
                            <i class="fa fa-users" aria-hidden="true"></i> Buscar Profesor
                        </button>
                    </div>

                    <div class="form-row d-none"> {{-- These fields are hidden and populated by the "Buscar Profesor" modal --}}
                        <div class="form-group col-md-6">
                            <label for="dni">CÉDULA Profesor</label>
                            <input type="text" name="dni" id="dni" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombreProfesor">Nombre del Profesor</label>
                            <input type="text" name="nombreProfesor" id="nombreProfesor" class="form-control"
                                readonly>
                        </div>
                    </div>
                    <div class="form-row d-none">
                        <div class="form-group col-md-6">
                            <label for="montoAPagar">Monto a Pagar</label>
                            <input type="text" name="montoAPagar" id="montoAPagar" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="idProfesor">ID del Profesor</label>
                            <input type="text" name="idProfesor" id="idProfesor" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Curso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>