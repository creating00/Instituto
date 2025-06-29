@extends('layouts.app')

@section('title', 'Gestión de Gastos')

@push('styles')
    <style>
        /* Asegurar que los modales secundarios estén por encima del primer modal */
        .modal {
            overflow-y: auto !important;
            /* Permitir scroll dentro del modal */
        }

        .modal-backdrop.show {
            opacity: 0.5;
            /* Ajustar la opacidad del backdrop */
        }

        .input-group.input-group-sm .input-group-text {
            border-right: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-4">
        <div class="btn-group flex-wrap mb-4" role="group" aria-label="Acciones">
            <button class="btn btn-outline-danger mb-2" type="button" data-toggle="modal" data-target="#nuevo_gastos">
                <i class="fas fa-plus"></i> Nuevo Gastos
            </button>
            <button class="btn btn-outline-success mb-2" type="button" data-toggle="modal" data-target="#nuevo_proveedor">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </button>
            <button class="btn btn-outline-info mb-2" type="button" data-toggle="modal" data-target="#nuevo_servicio">
                <i class="fas fa-plus"></i> Nuevo Producto o Servicio
            </button>
            <button class="btn btn-outline-info mb-2" type="button" data-toggle="modal" data-target="#profesor">
                <i class="fas fa-plus"></i> Pagos Docentes
            </button>
            <button class="btn btn-outline-info mb-2" type="button" data-toggle="modal" data-target="#imprimir">
                <i class="fa fa-print" aria-hidden="true"></i> Imprimir
            </button>
        </div>

        {{-- Alerta --}}
        @if (session('alert'))
            <div class="alert alert-warning">
                {{ session('alert') }}
            </div>
        @endif

        {{-- Tabla de gastos --}}
        <div class="table-responsive-lg">
            <table class="table table-striped table-bordered" id="gastoTable">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Servicio o Producto</th>
                        <th>Fecha y Hora</th>
                        <th>Total</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th hidden>Sede</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gastos as $gasto)
                        <tr>
                            <td>{{ $gasto->id }}</td>
                            <td>{{ $gasto->servicioProducto->descripcion ?? 'Sin descripción' }}</td>
                            <td>{{ $gasto->fecha }}</td>
                            <td>${{ number_format($gasto->total, 2, '.', ',') }}</td>
                            <td>{{ $gasto->mes }}</td>
                            <td>{{ $gasto->anio }}</td>
                            <td hidden>{{ $gasto->sede->nombre ?? 'Sin sede' }}</td>
                            <td>
                                <form action="{{ route('gastos.destroy', $gasto->id) }}" method="POST"
                                    class="d-inline confirmar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('modals')
    @include('modals.modal_gastos')
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (document.querySelector('#gastoTable')) {
                initDataTable('#gastoTable', {
                    order: [
                        [1, 'asc']
                    ]
                });
            }
        });
    </script>
@endpush
