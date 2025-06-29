<!-- Modal para guardar el mensaje -->
<div id="guardarMensajeModal" class="modal" tabindex="-1" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Configurar Mensaje de Recordatorio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrarModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formMensaje" method="POST" action="{{ route('configuracion.guardarMensaje') }}">
                    @csrf

                    <!-- Contenido del mensaje -->
                    <div class="form-group">
                        <label for="contenidoMensaje">Contenido del Mensaje</label>
                        <textarea class="form-control" name="contenidoMensaje" id="contenidoMensaje" rows="4"
                            placeholder="Escribe el mensaje...">{{ old('contenidoMensaje', $data['contenidoMensaje'] ?? '') }}</textarea>
                    </div>

                    <!-- Botones de variables -->
                    <div class="form-group">
                        <label>Variables disponibles:</label>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Variables">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="insertarComodin('{alumno}')">{alumno}</button>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="insertarComodin('{curso}')">{curso}</button>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="insertarComodin('{fecha}')">{fecha}</button>
                        </div>
                    </div>

                    <!-- Botón submit -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
