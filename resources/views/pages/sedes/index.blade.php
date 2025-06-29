@extends('layouts.app')

@section('title', 'Gestión de Sedes')

@section('content')
    <div class="container-fluid">
        <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="modal" data-target="#nueva_sede">
            <i class="fas fa-plus"></i> Nueva Sede
        </button>
    </div>

    @if (session('alert'))
        <div class="alert alert-info">{{ session('alert') }}</div>
    @endif

    <div class="table-responsive-lg">
        <table class="table table-striped table-bordered" id="sedeTable">
            <thead class="thead-dark">
                <tr>
                    <th>Sede</th>
                    <th>Provincia</th>
                    <th>Ciudad</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sedes as $sede)
                    <tr>
                        <td>{{ $sede->nombre }}</td>
                        <td>{{ $sede->provincia }}</td>
                        <td>{{ $sede->ciudad }}</td>
                        <td>{{ $sede->direccion }}</td>
                        <td>{{ $sede->email }}</td>
                        <td>{{ $sede->telefono }}</td>
                        <td>
                            @if ($sede->estado == 1)
                                <span class="badge badge-pill badge-success">Activo</span>
                            @else
                                <span class="badge badge-pill badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if ($sede->estado == 1)
                                <a href="{{ route('sedes.edit', $sede->id) }}" class="btn btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('sedes.destroy', $sede->id) }}" method="POST"
                                    class="d-inline confirmar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            @else
                                <a href="{{ route('sedes.activate', $sede->id) }}" class="btn btn-warning">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay sedes disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- {{ $sedes->links() }} --}} {{-- Eliminado porque usamos DataTables --}}
    </div>
@endsection

@push('modals')
    @include('modals.modal_sede_nueva')
@endpush

@push('scripts')
    <script>
        initDataTable('#sedeTable', {
            order: [
                [0, 'asc']
            ]
        });
    </script>
@endpush
