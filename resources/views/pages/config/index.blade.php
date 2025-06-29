@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Datos de la Empresa
                </div>
                <div class="card-body">
                    <form action="{{ route('configuracion.update') }}" method="POST" class="p-3">
                        @csrf
                        {{-- Si usás método PUT/PATCH para update --}}
                        @method('PUT')

                        <input type="hidden" name="id" value="{{ $data['id'] }}">

                        <div class="form-group mb-3">
                            <label for="txtNombre">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" id="txtNombre"
                                placeholder="Nombre de la Empresa" value="{{ old('nombre', $data['nombre']) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="txtTelEmpresa">Teléfono:</label>
                            <input type="number" name="telefono" class="form-control" id="txtTelEmpresa"
                                placeholder="Teléfono de la Empresa" value="{{ old('telefono', $data['telefono']) }}"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="txtEmailEmpresa">Correo Electrónico:</label>
                            <input type="email" name="email" class="form-control" id="txtEmailEmpresa"
                                placeholder="Correo de la Empresa" value="{{ old('email', $data['email']) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="txtDirEmpresa">Dirección:</label>
                            <input type="text" name="direccion" class="form-control" id="txtDirEmpresa"
                                placeholder="Dirección de la Empresa" value="{{ old('direccion', $data['direccion']) }}"
                                required>
                        </div>

                        @if (isset($alert))
                            <div class="alert alert-info">
                                {!! $alert !!}
                            </div>
                        @endif

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save"></i> Modificar Datos
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="mostrarModal()">
                                <i class="fas fa-envelope"></i> Configurar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('pages.config.guardar_mensaje')

    {{-- Scripts para mostrar/ocultar modal y manejar inserción de comodines --}}
    @push('scripts')
        <script>
            function mostrarModal() {
                document.getElementById('guardarMensajeModal').style.display = 'block';
            }

            function cerrarModal() {
                document.getElementById('guardarMensajeModal').style.display = 'none';
            }

            function insertarComodin(comodin) {
                let textarea = document.getElementById('contenidoMensaje');
                const startPos = textarea.selectionStart;
                const endPos = textarea.selectionEnd;
                const text = textarea.value;

                textarea.value = text.substring(0, startPos) + comodin + text.substring(endPos);
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = startPos + comodin.length;
            }

            // Opcional: manejar submit ajax para formMensaje
            document.getElementById('formMensaje').addEventListener('submit', function(e) {
                e.preventDefault();
                // Aquí podés agregar AJAX para enviar el contenido del mensaje sin recargar
                alert('Funcionalidad para guardar mensaje no implementada.');
            });
        </script>
    @endpush
@endsection
