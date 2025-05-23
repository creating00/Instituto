<?php
include_once "includes/header.php";
include "../conexion.php";

// Verificar si el usuario tiene permiso para editar uniformes
$id_user = $_SESSION['idUser'];
$permiso = "salas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

// Comprobar si el formulario fue enviado con el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $alert = "";
    $id_uniforme = $_POST['id']; // Obtener solo el id

    // Consulta para obtener los datos del uniforme basado en el id
    $sql = mysqli_query($conexion, "SELECT * FROM uniformes WHERE id_uniforme = '$id_uniforme'");
    $result_sql = mysqli_num_rows($sql);

    if ($result_sql == 0) {
        $alert = '<div class="alert alert-danger" role="alert">Uniforme no encontrado.</div>';
    } else {
        $data = mysqli_fetch_array($sql);
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $talla = $data['talla'];
        $genero = $data['genero'];
        $precio = $data['precio'];
        $stock = $data['stock'];
    }
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Uniforme
                </div>
                <div class="card-body">
                    <!-- Mostrar el mensaje de alerta si existe -->
                    <?php echo isset($alert) ? $alert : ''; ?>

                    <div id="alerta" class="alert" role="alert" style="display: none;">
                        <strong id="alertMessage"></strong>
                    </div>

                    <!-- Formulario para modificar el uniforme -->
                    <form id="editarUniformeForm">
                        <!-- Campo oculto para el ID del uniforme -->
                        <input type="hidden" name="id" value="<?php echo $id_uniforme; ?>">

                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo isset($nombre) ? $nombre : ''; ?>" required>
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control"><?php echo isset($descripcion) ? $descripcion : ''; ?></textarea>
                        </div>

                        <!-- Talla -->
                        <div class="form-group">
                            <label for="talla">Talla</label>
                            <select name="talla" id="talla" class="form-control" required>
                                <option value="16" <?php echo ($talla == '16') ? 'selected' : ''; ?>>16</option>
                                <option value="S" <?php echo ($talla == 'S') ? 'selected' : ''; ?>>S</option>
                                <option value="M" <?php echo ($talla == 'M') ? 'selected' : ''; ?>>M</option>
                                <option value="L" <?php echo ($talla == 'L') ? 'selected' : ''; ?>>L</option>
                                <option value="XL" <?php echo ($talla == 'XL') ? 'selected' : ''; ?>>XL</option>
                            </select>
                        </div>

                        <!-- Género -->
                        <div class="form-group" hidden>
                            <label for="genero">Género</label>
                            <select name="genero" id="genero" class="form-control" required>
                                <option value="Masculino" <?php echo ($genero == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo ($genero == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                <option value="Unisex" <?php echo ($genero == 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                        </div>

                        <!-- Precio -->
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" name="precio" id="precio" class="form-control" value="<?php echo isset($precio) ? $precio : ''; ?>" required>
                        </div>

                        <!-- Stock -->
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control" value="<?php echo isset($stock) ? $stock : ''; ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Uniforme</button>
                        <a href="uniformes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Cuando el formulario sea enviado
        $('#editarUniformeForm').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            // Recoger los datos del formulario
            var formData = new FormData(this);

            // Enviar los datos por AJAX
            $.ajax({
                url: 'actualizar_uniforme.php',
                type: 'POST',
                data: formData,
                processData: false, // No procesar los datos
                contentType: false, // No establecer el tipo de contenido
                success: function(response) {
                    console.log(response);
                    // Mostrar la respuesta
                    var data = response;

                    if (data.success) {
                        $('#alerta').removeClass('alert-danger').addClass('alert-success')
                            .find('#alertMessage').text(data.message)
                            .end()
                            .fadeIn(500); // Aparecer con una animación suave

                        setTimeout(function() {
                            window.location.href = 'uniformes.php'; // Redirigir
                        }, 2000);
                    } else {
                        $('#alerta').removeClass('alert-success').addClass('alert-danger')
                            .find('#alertMessage').text(data.message)
                            .end()
                            .fadeIn(500);
                    }

                    // Ocultar la alerta después de 5 segundos
                    setTimeout(function() {
                        $('#alerta').fadeOut(500); // Desaparecer con una animación suave
                    }, 5000);
                },
                error: function() {
                    $('#alerta').removeClass('alert-success').addClass('alert-danger')
                        .find('#alertMessage').text('Hubo un error al procesar la solicitud.')
                        .end()
                        .fadeIn(500);
                }
            });
        });
    });
</script>

<?php include_once "includes/footer.php"; ?>