<div id="nueva_sede" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Sede</h5>
                <button class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sedes.store') }}" method="POST" autocomplete="off">
                    @csrf

                    {{-- Alertas --}}
                    @if (session('alert'))
                        <div class="alert alert-info">{{ session('alert') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre de Sede</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    placeholder="Ingrese Nombre de la Sede" name="nombre" id="nombre"
                                    value="{{ old('nombre') }}">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="provincia">Provincia</label>
                                <select name="provincia" id="provincia"
                                    class="form-control @error('provincia') is-invalid @enderror">
                                    <option value="">Seleccione Provincia</option>
                                    @foreach (['BUENOS AIRES', 'CABA', 'CATAMARCA', 'CHACO', 'CHUBUT', 'CORDOBA', 'CORRIENTES', 'ENTRE RIOS', 'FORMOSA', 'JUJUY', 'LA PAMPA', 'LA RIOJA', 'MENDOZA', 'MISIONES', 'NEUQUEN', 'RIO NEGRO', 'SALTA', 'SAN JUAN', 'SAN LUIS', 'SANTA CRUZ', 'SANTA FE', 'SANTIAGO DEL ESTERO', 'TIERRA DE FUEGO', 'TUCUMAN'] as $prov)
                                        <option value="{{ $prov }}"
                                            {{ old('provincia') == $prov ? 'selected' : '' }}>
                                            {{ $prov }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('provincia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror"
                                    placeholder="Ingrese Ciudad" name="ciudad" id="ciudad"
                                    value="{{ old('ciudad') }}">
                                @error('ciudad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    placeholder="Ingrese Dirección" name="direccion" id="direccion"
                                    value="{{ old('direccion') }}">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Ingrese Correo Electrónico" name="email" id="email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="number" class="form-control @error('telefono') is-invalid @enderror"
                                    placeholder="Ingrese Teléfono" name="telefono" id="telefono"
                                    value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Guardar Sede" class="btn btn-primary mt-3">
                </form>
            </div>
        </div>
    </div>
</div>