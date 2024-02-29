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
        <a class="navbar-brand" href="index.php">Eduser Sistema</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#"  data-toggle="modal" data-target="#nuevo_pass"><i class="fa fa-user" aria-hidden="true"></i>Usuario: <?php echo $_SESSION['user']; ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="salir.php"><i class="fa fa-external-link" aria-hidden="true"></i>Cerrar Sessión</a>
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
                            <div class="sb-nav-link-icon"><i class="fa fa-credit-card" aria-hidden="true" style="color:#FFFFFF;"></i></div>
                            Cobrar Cuota
                        </a>
                        <a class="nav-link" href="inscripcion.php">
                            <div class="sb-nav-link-icon"><i class="fa fa-address-card" aria-hidden="true" style="color:#FFFFFF;"></i></div>
                            Inscribir
                        </a>
                        <a class="nav-link" href="usuarios.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user" style="color:#FFFFFF;"></i></div>
                            Usuarios
                        </a>
                        <a class="nav-link" href="gestionCobros.php">
                            <div class="sb-nav-link-icon"><i class="fa fa-industry" aria-hidden="true" style="color:#FFFFFF;"></i></div>
                            Gestion Cobranzas Usuario
                        </a>

                        
                        <a class="nav-link" href="gastos.php">
                            <div class="sb-nav-link-icon"><i class="fa fa-minus" aria-hidden="true" style="color:#FFFFFF;"></i></div>
                            Control de Gastos
                        </a>
                        
                        <a class="nav-link" data-toggle="modal" data-target="#correos" >
               
                            <div class="sb-nav-link-icon"> <i class="fa fa-envelope-open" aria-hidden="true" style="color:#FFFFFF;"></i></div>
                            Enviar Correos
                        </a>
                        <a class="nav-link" href="config.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cogs" style="color:#FFFFFF;"></i></div>
                            Sobre Nosotros
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid mt-2">
 <!-- Modal de correos electronicos -->
  <div class="modal" tabindex="-1" id="correos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar Correos.<i class="fa fa-envelope-open" aria-hidden="true"></i></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p> Sistema Eduser.</p>
        <form id="form1" class="well col-lg-12" action="enviar.php" method="post" name="form1" enctype="multipart/form-data">
        <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Operador @</span>
        <input type="text" id="Nombre" name="Nombre" value="<?php echo $_SESSION['nombre']; ?>"  placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" style="width:64%;">
        </div>
        <div class="input-group mb-3">
        <input type="text" id="Email" name="Email" style="width:59%;" placeholder="Email del alumno" aria-label="Recipient's username" aria-describedby="basic-addon2">
        <span class="input-group-text" id="basic-addon2">@example.com</span>
        </div>
        <div class="input-group">
        <span class="input-group-text">Mensaje</span>
        <textarea id="Mensaje" name="Mensaje" style="width:80%; height:80px;"aria-label="With textarea"></textarea>
        </div><br>
        <div class="input-group">
        <input type="file" name="adjunto" id="archivo-adjunto">
        </div><br>

        <button type="submit" class="btn btn-primary">Enviar Correo</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
      </form>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>            