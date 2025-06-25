<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title', 'Panel de Administración')</title>

    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" crossorigin="anonymous" />
    <link href="{{ asset('assets/js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet" />

    @stack('styles')
</head>

<body class="sb-nav-fixed">
    @auth
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ url('dashboard') }}"
                style="position: relative; width: 150px; padding-left: 40px;">
                <img src="{{ asset('assets/img/1.png') }}" alt="SOFTESC Logo"
                    style="height: 40px; position: absolute; top: -10px; left: 0; z-index: 1050;">
            </a>

            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#nuevo_pass">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            Usuario: {{ auth()->user()->name }}
                        </a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar Sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="{{ url('cuotas') }}">
                                <div class="sb-nav-link-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
                                Cobrar Pago
                            </a>
                            <a class="nav-link" href="{{ url('inscripcion') }}">
                                <div class="sb-nav-link-icon"><i class="fa fa-address-card" aria-hidden="true"></i></div>
                                Inscribir
                            </a>
                            <a class="nav-link" href="{{ url('usuarios') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Usuarios
                            </a>
                            <a class="nav-link" href="{{ url('estadisticas') }}">
                                <div class="sb-nav-link-icon"><i class="fa fa-industry" aria-hidden="true"></i></div>
                                Gestión de Estudiantes
                            </a>
                            <a class="nav-link" href="{{ url('historial_alumno') }}">
                                <div class="sb-nav-link-icon"><i class="fa fa-industry" aria-hidden="true"></i></div>
                                Historial
                            </a>
                            <a class="nav-link" href="{{ url('gastos') }}">
                                <div class="sb-nav-link-icon"><i class="fa fa-minus" aria-hidden="true"></i></div>
                                Control de Gastos
                            </a>
                            <a class="nav-link" href="{{ url('config') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                                Sobre Nosotros
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid mt-2">
                        @yield('content')
                    </div>
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">
                                Derechos reservados &copy;
                                <a href="https://eduser.com.ar/" target="_blank" rel="noopener noreferrer">Creating
                                    SR</a>
                                {{ date('Y') }}
                            </div>
                            <div>
                                <a href="#">Privacy Policy</a> &middot; <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    @endauth

    @guest
        {{-- Opcional: contenido para invitados o redirección --}}
    @endguest
    @stack('modals')

    <script src="{{ asset('assets/js/all.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/funciones.js') }}"></script>
    @stack('scripts')
</body>

</html>
