<div id="nueva_sala" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSalaTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevaSalaTitle">Nueva Sala</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('salas.store') }}" method="POST" autocomplete="off">
                    @csrf

                    @if(session('alert'))
                        <div class="alert alert-info">
                            {{ session('alert') }}
                        </div>
                    @endif

                    @error('sala')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                        <label for="sala">Sala</label>
                        <input type="text" class="form-control @error('sala') is-invalid @enderror" name="sala" id="sala" placeholder="Ingrese Sala" value="{{ old('sala') }}" required>
                    </div>

                    @error('descripcion')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion" placeholder="Ingrese Descripción" value="{{ old('descripcion') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Sala</button>
                </form>
            </div>
        </div>
    </div>
</div>
