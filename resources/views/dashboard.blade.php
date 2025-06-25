@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray">Panel Principal</h1>
    </div>
    <div class="row justify-content-center">
        @php
            $items = [
                [
                    'link' => 'usuarios',
                    'color' => '#f94144',
                    'icon' => 'fa-user',
                    'label' => 'Usuarios',
                    'value' => $totalU,
                ],
                [
                    'link' => 'alumnos',
                    'color' => '#f3722c',
                    'icon' => 'fa-graduation-cap',
                    'label' => 'Estudiantes',
                    'value' => $totalA,
                ],
                [
                    'link' => 'cursos',
                    'color' => '#f9c74f',
                    'icon' => 'fa-tasks',
                    'label' => 'Cursos',
                    'value' => $totalC,
                ],
                [
                    'link' => 'profesor',
                    'color' => '#90be6d',
                    'icon' => 'fa-users',
                    'label' => 'Profesores',
                    'value' => $totalP,
                ],
                [
                    'link' => 'sala',
                    'color' => '#43aa8b',
                    'icon' => 'fa-window-restore',
                    'label' => 'Salas',
                    'value' => $totalSa,
                ],
            ];
        @endphp

        @foreach ($items as $item)
            <a href="{{ url($item['link']) }}" class="text-decoration-none text-center m-3">
                <div class="dashboard-circle" style="background-color: {{ $item['color'] }};">
                    <i class="fa {{ $item['icon'] }} fa-2x mb-2"></i>
                    <strong class="text-uppercase">{{ $item['label'] }}</strong>
                    <div class="h5">{{ $item['value'] }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endsection

@push('modals')
    @include('modals.cambiar_password')
@endpush