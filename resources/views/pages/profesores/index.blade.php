@extends('layouts.app')

@section('title', 'Gestión de Profesores')

@section('content')
    <div class="container-fluid">
        <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="modal" data-target="#nuevo_profesor">
            Nuevo Profesor <i class="fas fa-plus"></i>
        </button>
    </div>

    @if(session('alert'))
        <div class="alert alert-info mt-3">
            {{ session('alert') }}
        </div>
    @endif

    <div class="table-responsive-lg mt-3">
        <table class="table table-hover table-striped table-bordered" id="profesorTable">
            <thead class="thead-dark">
                <tr>
                    <th>CÉDULA</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Celular</th>
                    <th>Email</th>
                    <th hidden>Sede</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($profesores as $profesor)
                    <tr>
                        <td>{{ $profesor->dni }}</td>
                        <td>{{ $profesor->nombre }}</td>
                        <td>{{ $profesor->apellido }}</td>
                        <td>{{ $profesor->direccion }}</td>
                        <td>{{ $profesor->celular }}</td>
                        <td>{{ $profesor->email }}</td>
                        <td hidden>{{ $profesor->sede->nombre ?? '' }}</td>
                        <td>
                            @if ($profesor->estado)
                                <span class="badge badge-pill badge-success">Activo</span>
                            @else
                                <span class="badge badge-pill badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if ($profesor->estado)
                                <a href="{{ route('profesores.edit', $profesor->idprofesor) }}" class="btn btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('profesores.destroy', $profesor->idprofesor) }}" method="POST" class="d-inline confirmar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('profesores.activate', $profesor->idprofesor) }}" class="btn btn-warning">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $profesores->links() }}
    </div>
@endsection

@push('modals')
    @include('modals.modal_profesor_nuevo')
@endpush

@push('scripts')
    <script>
        initDataTable('#profesorTable', {
            order: [[0, 'asc']]
        });
    </script>
@endpush
