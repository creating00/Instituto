<?php include_once "includes/header.php";
    include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "correo";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    //header("Location: permisos.php");
}


?>
<button type="button" class="btn btn-outline-info"  data-toggle="modal" data-target="#correos">Abrir Panel</button>

<div class="modal" tabindex="-1" id="correos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar Correos Sistema Eduser.</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p> Correo.</p>
        <form id="form1" class="well col-lg-12" action="enviar.php" method="post" name="form1" enctype="multipart/form-data">
        <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Operador @</span>
        <input type="text" id="Nombre" name="Nombre" value="<?php echo $_SESSION['nombre']; ?>"  placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" style="width:44%;">
        </div>
        <div class="input-group mb-3">
        <input type="text" id="Email" name="Email" style="width:40%;" placeholder="Email del alumno" aria-label="Recipient's username" aria-describedby="basic-addon2">
        <span class="input-group-text" id="basic-addon2">@example.com</span>
        </div>
        <div class="input-group">
        <span class="input-group-text">Mensaje</span>
        <textarea id="Mensaje" name="Mensaje" style="width:70%; height:80px;" aria-label="With textarea"></textarea>
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
<?php include_once "includes/footer.php"; ?>