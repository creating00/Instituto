<?php
include_once "includes/header.php";
require_once "../conexion.php";
require_once "../src/mensajeRecordatorio.php";

$id_user = $_SESSION['idUser'];
$permiso = "configuracion";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
$query = mysqli_query($conexion, "SELECT * FROM configuracion");
$data = mysqli_fetch_assoc($query);
if ($_POST) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['email']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">
            Todo los campos son obligatorios
        </div>';
    } else {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $id = $_POST['id'];
        $update = mysqli_query($conexion, "UPDATE configuracion SET nombre = '$nombre', telefono = '$telefono', email = '$email', direccion = '$direccion' WHERE id = $id");
        if ($update) {
            $alert = '<div class="alert alert-success" role="alert">
            Datos modificado
        </div>';
        }
    }
}
// Inicia la clase MensajeRecordatorio
$mensajeRecordatorio = new MensajeRecordatorio($conexion);

// Obtén el contenido del mensaje
$contenidoMensaje = $mensajeRecordatorio->obtenerMensajeTexto();  // O usar obtenerMensajeJson()

?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Datos de la Empresa
            </div>
            <div class="card-body">
                <form action="" method="post" class="p-3">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                        <input type="text" name="nombre" class="form-control" value="<?php echo $data['nombre']; ?>" id="txtNombre" placeholder="Nombre de la Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="number" name="telefono" class="form-control" value="<?php echo $data['telefono']; ?>" id="txtTelEmpresa" placeholder="Teléfono de la Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" id="txtEmailEmpresa" placeholder="Correo de la Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo $data['direccion']; ?>" id="txtDirEmpresa" placeholder="Dirección de la Empresa" required>
                    </div>
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar Datos</button>
                        <!-- Botón para abrir el modal -->
                        <button type="button" class="btn btn-secondary" onclick="mostrarModal()">
                            <i class="fas fa-envelope"></i> Configurar Mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para guardar el mensaje -->
<div id="guardarMensajeModal" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configurar Mensaje de Recordatorio</h5>
                <button type="button" class="btn-close" onclick="cerrarModal()">×</button>
            </div>
            <div class="modal-body">
                <form id="formMensaje">
                    <div class="form-group">
                        <label for="contenidoMensaje">Contenido del Mensaje</label>
                        <textarea class="form-control" name="contenidoMensaje" id="contenidoMensaje" rows="4" placeholder="Escribe el mensaje..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Variables:</label>
                        <button type="button" class="btn btn-secondary" onclick="insertarComodin('{alumno}')">{alumno}</button>
                        <button type="button" class="btn btn-secondary" onclick="insertarComodin('{curso}')">{curso}</button>
                        <button type="button" class="btn btn-secondary" onclick="insertarComodin('{fecha}')">{fecha}</button>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Éxito -->
<div id="exitoModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">¡Éxito!</h5>
                <button type="button" class="btn-close" onclick="cerrarExitoModal()">×</button>
            </div>
            <div class="modal-body">
                <p id="mensajeExito">El mensaje fue guardado correctamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="cerrarExitoModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Error (opcional) -->
<div id="errorModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">¡Error!</h5>
                <button type="button" class="btn-close" onclick="cerrarErrorModal()">×</button>
            </div>
            <div class="modal-body">
                <p id="mensajeError">Hubo un problema al guardar el mensaje.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="cerrarErrorModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Estilo básico para el modal -->
<style>
    .modal {
        display: none;
        /* Ocultar por defecto */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Fondo oscuro */
        justify-content: center;
        align-items: center;
    }

    .modal-dialog {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        width: 70%;
        max-width: 600px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
    }

    .modal-body {
        margin-top: 15px;
    }

    .btn-close {
        cursor: pointer;
        font-size: 20px;
    }

    .show {
        display: flex;
        /* Mostrar el modal */
    }
</style>
<script>

    // Pasar el contenido del mensaje desde PHP a JavaScript
    var contenidoMensaje = <?php echo json_encode($contenidoMensaje); ?>;

    // Función para mostrar el modal
    function mostrarModal() {
        // Mostrar el modal
        document.getElementById('guardarMensajeModal').classList.add('show');

        // Asignar el contenido recibido al campo de texto (el mensaje ya está disponible desde PHP)
        document.getElementById('contenidoMensaje').value = contenidoMensaje;

        // Colocar foco en el campo de mensaje
        document.getElementById('contenidoMensaje').focus();
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('guardarMensajeModal').classList.remove('show'); // Eliminar la clase para cerrar el modal
    }

    // Función para insertar comodines en el campo de mensaje
    function insertarComodin(comodin) {
        const contenidoMensaje = document.getElementById('contenidoMensaje');
        contenidoMensaje.value += " " + comodin; // Inserta el comodín en el texto
        contenidoMensaje.focus(); // Coloca el cursor al final
    }

    // Función para mostrar el modal de éxito
    function mostrarExitoModal(mensaje) {
        document.getElementById('mensajeExito').innerText = mensaje; // Coloca el mensaje
        document.getElementById('exitoModal').classList.add('show'); // Mostrar el modal
    }

    // Función para cerrar el modal de éxito
    function cerrarExitoModal() {
        document.getElementById('exitoModal').classList.remove('show'); // Eliminar la clase para cerrar el modal
    }

    // Función para mostrar el modal de error
    function mostrarErrorModal(mensaje) {
        document.getElementById('mensajeError').innerText = mensaje; // Coloca el mensaje
        document.getElementById('errorModal').classList.add('show'); // Mostrar el modal
    }

    // Función para cerrar el modal de error
    function cerrarErrorModal() {
        document.getElementById('errorModal').classList.remove('show'); // Eliminar la clase para cerrar el modal
    }

    // Manejo del envío del formulario con AJAX
    document.getElementById('formMensaje').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío tradicional del formulario

        // Obtener los datos del formulario
        const formData = new FormData(this);

        // Hacer la solicitud AJAX
        fetch('guardar_mensaje.php', {
                method: 'POST',
                body: formData // Enviar los datos del formulario
            })
            .then(response => response.text()) // Obtener la respuesta como texto
            .then(result => {
                console.log("Resultado del servidor:", result); // Mostrar el resultado crudo

                if (result === "true") {
                    mostrarExitoModal("Mensaje guardado correctamente."); // Mostrar modal de éxito
                } else {
                    mostrarErrorModal("Hubo un error al guardar el mensaje."); // Mostrar modal de error
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarErrorModal("Hubo un error al guardar los cambios.");
            });

    });
</script>
<?php include_once "includes/footer.php"; ?>