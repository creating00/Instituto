@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <i class="fa fa-university fa-5x mr-3" aria-hidden="true"></i>
        <h1>Ganancias</h1>
    </div>

    <div class="container mt-4">
        <form>
            <!-- Pestañas -->
            <ul class="nav nav-tabs" id="gananciasTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mensuales-tab" data-toggle="tab" href="#mensuales" role="tab">Ganancias
                        Mensuales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="informe-detallado-tab" data-toggle="tab" href="#informe-detallado"
                        role="tab">Informe Detallado</a>
                </li>
            </ul>

            <div class="tab-content mt-4" id="gananciasContent">
                <!-- GANANCIAS MENSUALES -->
                <div class="tab-pane fade show active" id="mensuales" role="tabpanel">
                    <div class="form-row align-items-center">
                        <div class="form-group col-md-6">
                            <label for="idusuario" style="font-size: 20px">
                                <i class="fas fa-user"></i> Usuarios:
                            </label>
                            <select name="idusuario" class="form-control" id="idusuario" onchange="ShowSelected();">
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->idusuario }}">
                                        {{ $usuario->usuario }} ({{ $usuario->sede ? $usuario->sede->nombre : 'Sin sede' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="mes" style="font-size: 20px">
                                <i class="fa fa-calendar"></i> Mes:
                            </label>
                            <select name="mes" class="form-control" id="mes" onchange="ShowSelected();">
                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                    <option value="{{ $mes }}" @if ($mes == 'Marzo') selected @endif>
                                        {{ $mes }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Selecciona un mes para filtrar</small>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="anio"><i class="fa fa-circle"></i> Año:</label>
                            <input type="number" class="form-control" value="{{ $anio }}" id="anio"
                                name="anio" placeholder="Ingrese un Año">
                            <small class="text-muted">Selecciona o ingresa un año para filtrar</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="fechaFiltro" style="font-size: 20px">Filtrar por Fecha:</label>
                            <input type="date" id="fechaFiltro" name="fechaFiltro" class="form-control">
                            <small class="text-muted">Elige una fecha específica para filtrar</small>
                        </div>
                    </div>

                    <p class="text-center text-primary">Elige uno de los filtros: Mes/Año o Fecha</p>

                    <div class="table-responsive mt-3">
                        <div id="mostrar_cuotas"></div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary"
                            onclick="total_ganancias(); total_ganancias_In(); totalExamenn();" data-toggle="modal"
                            data-target="#ganancias">
                            <i class="fa fa-money"></i> Buscar
                        </button>
                        <input type="hidden" id="mes1" name="mes1" value="Marzo">
                    </div>
                </div>

                <!-- INFORME DETALLADO -->
                <div class="tab-pane fade" id="informe-detallado" role="tabpanel">
                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label for="cursoSeleccionado">Seleccionar Curso:</label>
                            <select name="cursoSeleccionado" class="form-control" id="cursoSeleccionado">
                                <option value="">Seleccione un curso</option>
                                {{-- Si tenés una lista de cursos, agregá aquí un @foreach --}}
                                {{-- @foreach ($cursos as $curso) --}}
                                {{-- <option value="{{ $curso->id }}">{{ $curso->nombre }}</option> --}}
                                {{-- @endforeach --}}
                            </select>
                            <small class="text-muted">Selecciona un curso para filtrar</small>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="mesInicio">Mes de Inicio:</label>
                            <select name="mesInicio" class="form-control" id="mesInicio">
                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                    <option value="{{ $mes }}">{{ $mes }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Selecciona el mes de inicio</small>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="anioInicio">Año de Inicio:</label>
                            <input type="number" class="form-control" value="{{ $anio }}" id="anioInicio"
                                name="anioInicio" placeholder="Ingrese el Año de inicio">
                            <small class="text-muted">Selecciona el año de inicio</small>
                        </div>
                    </div>

                    <hr style="border-top: 2px solid #ccc; margin: 10px 0;">

                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label for="fechaInicio">Fecha de Inicio Específica:</label>
                            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control">
                            <small class="text-muted">O selecciona una fecha de inicio</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fechaFin">Fecha Fin Específica:</label>
                            <input type="date" id="fechaFin" name="fechaFin" class="form-control">
                            <small class="text-muted">O selecciona una fecha de fin</small>
                        </div>
                    </div>

                    <hr style="border-top: 2px solid #ccc; margin: 10px 0;">
                    <p class="text-center text-primary">Puedes filtrar por mes/año o por fechas específicas</p>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Alumno</th>
                                    <th>Concepto de Cuota</th>
                                    <th>Monto Original</th>
                                    <th>Mora</th>
                                    <th>Total Pagado</th>
                                    <th>Fecha de Pago</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="detalleGanancias">
                                {{-- Aquí se cargarán los datos dinámicamente con JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="resumenInforme" class="mt-4"></div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary" onclick="generarInformeDetallado();">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                        <button type="button" class="btn btn-success" onclick="exportarPDF();" hidden>
                            <i class="fa fa-file-pdf"></i> Exportar PDF
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal de Ganancias --}}
    @include('pages.ganancias.modal')
@endsection
