{{-- resources/views/modals/cambiar_password.blade.php --}}
<div id="nuevo_pass" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-header text-white">
                <h5 class="modal-title">Cambiar contraseña</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmPass">
                    @csrf
                    <div class="form-group">
                        <label for="actual"><i class="fas fa-key"></i> Contraseña Actual</label>
                        <input id="actual" class="form-control" type="password" name="actual"
                            placeholder="Contraseña actual" required>
                    </div>
                    <div class="form-group">
                        <label for="nueva"><i class="fas fa-key"></i> Contraseña Nueva</label>
                        <input id="nueva" class="form-control" type="password" name="nueva"
                            placeholder="Contraseña nueva" required>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="btnCambiar(event)">Cambiar</button>
                </form>
            </div>
        </div>
    </div>
</div>