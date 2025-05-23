<div id="modalCuotasPendientes" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pagos Pendientes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El alumno tiene <strong id="cuotasPendientes"></strong> pagos pendientes.</p>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="window.location.href='historial_cuotas.php?idAlumno=' + document.getElementById('alumno_id').value + '&dni=' + document.getElementById('dni').value;">
                    Ir al Detalle de Pagos
                </button>
            </div>
        </div>
    </div>
</div>