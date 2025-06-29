<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Iniciar Sesión</title>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet" />
</head>

<body class="bg-primary-login">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card modern-login-card shadow-lg">
                    <div class="card-header text-center modern-card-header">
                        <img src="{{ asset('assets/img/1.png') }}"
                            class="img-thumbnail mb-3 mx-auto d-block modern-logo" width="100" alt="Logo" />
                        <h3 class="modern-heading">Iniciar Sesión</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <form action="{{ route('login') }}" method="POST" novalidate>
                            @csrf
                            <div class="mb-3 position-relative">
                                <label for="usuario" class="form-label modern-label"><i class="fas fa-user"></i>
                                    Usuario</label>
                                <input class="form-control modern-input" type="text" name="usuario" id="usuario"
                                    required value="{{ old('usuario') }}" autocomplete="username" autofocus
                                    placeholder="Ingrese su usuario" />
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="clave" class="form-label modern-label"><i class="fas fa-key"></i>
                                    Clave</label>
                                <input class="form-control modern-input" type="password" name="clave" id="clave"
                                    required autocomplete="current-password" placeholder="Ingrese su contraseña" />
                            </div>
                            <button class="btn btn-primary w-100 modern-button" type="submit">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="custom-footer text-center text-muted small mt-5 py-3">
        Copyright &copy; <a href="http://www.eduservirtual.com.ar/" target="_blank"
            rel="noopener noreferrer">Creating</a> {{ date('Y') }}<br />
        <a href="#">Privacy Policy</a> &middot; <a href="#">Terms &amp; Conditions</a>
    </footer>

    <!-- Scripts al final para mejor performance -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/all.min.js') }}"></script> <!-- FontAwesome JS -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>

</html>
