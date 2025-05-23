<!-- Modal de confirmación de eliminación para cuotas -->
<div class="modal fade" id="confirmarEliminacionCuotaModal" tabindex="-1" aria-labelledby="confirmarEliminacionCuotaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="eliminar_cuota_post.php">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmarEliminacionCuotaModalLabel">Confirmar Eliminación de Pago</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro de que desea eliminar o actualizar esta pago?</p>
          <input type="hidden" name="idcuota" id="idcuota">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar/Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal para usuarios con confirmación de administrador para cuotas -->
<div class="modal fade" id="adminConfirmacionCuotaModal" tabindex="-1" aria-labelledby="adminConfirmacionCuotaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="form-admin-confirmacion-cuota" method="POST" action="cuotas.php">
      <input type="hidden" name="action" value="eliminar_cuota">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminConfirmacionCuotaModalLabel">Confirmación de Administrador para Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idcuota" name="idcuota">
          <div class="form-group">
            <label for="password">Ingrese la contraseña del administrador:</label>
            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Confirmar</button>
        </div>
      </div>
    </form>
  </div>
</div>