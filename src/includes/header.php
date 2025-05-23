<?php session_start();
if (empty($_SESSION['active'])) {
    header('location: ../');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Panel de Administración</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Software Escolar</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#nuevo_pass"><i class="fa fa-user" aria-hidden="true"></i>Usuario: <?php echo $_SESSION['user']; ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="salir.php">Cerrar Sesión</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="cuotas.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
                        Cobrar Pago
                    </a>
                    <a class="nav-link" href="inscripcion.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-address-card" aria-hidden="true"></i></div>
                        Inscribir
                    </a>
                    <a class="nav-link" href="usuarios.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        Usuarios
                    </a>
                    <a class="nav-link" href="estadisticas.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-industry" aria-hidden="true"></i></div>
                        Gestión de Estudiantes
                    </a>
                    <a class="nav-link" href="historial_alumno.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-industry" aria-hidden="true"></i></div>
                        Historial
                    </a>
                    <a class="nav-link" href="gastos.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-minus" aria-hidden="true"></i></div>
                        Control de Gastos
                    </a>
                    <a class="nav-link" href="config.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                        Sobre Nosotros
                    </a>
                </div>

                <!-- Imagen (logo u otro) al final -->
                <div class="sidebar-footer">
                <img src="../../assets/img//1.png" alt="Logo" style="width: 224px; height: auto; border-radius: 8px;">
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid mt-2">
