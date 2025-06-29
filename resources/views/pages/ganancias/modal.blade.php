<div class="modal fade" id="ganancias" tabindex="-1" role="dialog" aria-labelledby="gananciasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganancias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @foreach ([['label' => 'Ganancia Total Pagos:', 'id' => 'totall', 'value' => 0], ['label' => 'Ganancia Total Examen:', 'id' => 'total4'], ['label' => 'Ganancia Total Inscripción:', 'id' => 'total2', 'value' => 0], ['label' => 'Total:', 'id' => 'total5']] as $item)
                    <div class="form-group">
                        <label style="font-size: 24px">
                            @if ($item['label'] == 'Total:')
                                <i class="fa fa-shopping-bag"></i>
                            @endif
                            {{ $item['label'] }}
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" id="{{ $item['id'] }}" name="total"
                                value="{{ $item['value'] ?? '' }}" class="form-control text-center">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>