@extends('layouts.app')

@section('title', 'Gestión de Salas')

@section('content')
    <div class="container-fluid">
        <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="modal" data-target="#nueva_sala">
            Nueva Sala <i class="fas fa-plus"></i>
        </button>
    </div>

    @if (session('alert'))
        <div class="alert alert-info mt-3">
            {{ session('alert') }}
        </div>
    @endif

    <div class="table-responsive-lg mt-3">
        <table class="table table-hover table-striped table-bordered" id="salaTable">
            <thead class="thead-dark">
                <tr>
                    <th>Sala</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salas as $sala)
                    <tr>
                        <td>{{ $sala->sala }}</td>
                        <td>{{ $sala->descripcion }}</td>
                        <td>
                            @if ($sala->estado == 1)
                                <span class="badge badge-pill badge-success">Activo</span>
                            @else
                                <span class="badge badge-pill badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if ($sala->estado == 1)
                                <a href="{{ route('salas.edit', $sala->idsala) }}" class="btn btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('salas.destroy', $sala->idsala) }}" method="POST"
                                    class="d-inline confirmar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('salas.activate', $sala->idsala) }}" class="btn btn-warning">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $salas->links() }}
    </div>
@endsection

@push('modals')
    @include('modals.modal_sala_nueva')
@endpush

@push('scripts')
    <script>
        initDataTable('#salaTable', {
            order: [
                [0, 'asc']
            ]
        });
    </script>
@endpush
