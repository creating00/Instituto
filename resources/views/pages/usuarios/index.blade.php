@extends('layouts.app') {{-- O tu layout base --}}

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="container-fluid">
        <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="modal" data-target="#nuevo_usuario">
            Nuevo Usuario <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="table-responsive-lg mt-3">
        <table class="table table-hover table-striped table-bordered" id="userTable">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                    <th hidden>Sede</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>{{ $usuario->usuario }}</td>
                        <td hidden>{{-- $usuario->sede->nombre ?? '' --}}</td>
                        <td>
                            @if ($usuario->estado)
                                <span class="badge badge-pill badge-success">Activo</span>
                            @else
                                <span class="badge badge-pill badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if ($usuario->estado)
                                <a href="{{ route('usuarios.edit', $usuario->idusuario) }}" class="btn btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('usuarios.destroy', $usuario->idusuario) }}" method="POST"
                                    class="d-inline confirmar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            @else
                                <a href="{{ route('usuarios.activate', $usuario->idusuario) }}" class="btn btn-warning">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $usuarios->links() }} {{-- Paginación --}}
    </div>
@endsection

@push('modals')
    @include('modals.modal_usuario_nuevo')
@endpush

@push('scripts')
    <script>
        initDataTable('#userTable', {
            order: [
                [0, 'asc']
            ] // Columna 0 = "Nombre", ascendente
        });
    </script>
@endpush
