@extends('layouts.app')

@section('title', 'Modificar Usuario')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Usuario
                </div>
                <div class="card-body">

                    {{-- Mostrar mensaje de alerta si existe --}}
                    @if (session('alert'))
                        <div class="alert alert-info">
                            {{ session('alert') }}
                        </div>
                    @endif

                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Para método PUT o PATCH en formulario --}}

                        <input type="hidden" name="id" value="{{ $usuario->id }}">

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" placeholder="Ingrese nombre" class="form-control" name="nombre"
                                id="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" placeholder="Ingrese correo" class="form-control" name="correo"
                                id="correo" value="{{ old('correo', $usuario->correo) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <input type="text" placeholder="Ingrese usuario" class="form-control" name="usuario"
                                id="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
                        </div>

                        {{-- Campo oculto sede si lo necesitas --}}
                        <div class="form-group" hidden>
                            <label for="sede">Sede</label>
                            <select name="sede" class="form-control" id="sede">
                                {{-- @foreach ($sedes as $sede)
                                    <option value="{{ $sede->nombre }}"
                                        {{ old('sede', $usuario->sede->nombre ?? '') == $sede->nombre ? 'selected' : '' }}>
                                        {{ $sede->nombre }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roles">Rol</label>
                            <select name="roles" class="form-control" id="roles" required>
                                {{-- @forelse ($roles as $rol)
                                    <option value="{{ $rol->idrol }}"
                                        {{ old('roles', $usuario->id_rol) == $rol->idrol ? 'selected' : '' }}>
                                        {{ strtoupper($rol->nombrerol) }}
                                    </option>
                                @empty
                                    <option value="">No hay roles disponibles</option>
                                @endforelse --}}
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-edit"></i> Modificar
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-danger">Atrás</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection