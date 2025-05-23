<?php
session_start();
require("../conexion.php");
header('Content-Type: application/json'); // Indicar que se devuelve JSON

// Verificar si el usuario está logueado
if (!isset($_SESSION['idUser'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debe iniciar sesión para realizar esta acción.']);
    exit();
}

$id_user = $_SESSION['idUser']; // ID del usuario actual
$permiso = "cobrar"; // Permiso requerido

// Verificar si el usuario tiene el permiso o es administrador
$sql = mysqli_query($conexion, "SELECT 1 FROM permisos p 
                                INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
                                WHERE d.id_usuario = '$id_user' AND p.nombre = '$permiso'");
$existe = mysqli_fetch_assoc($sql);

if (!$existe && $id_user != 1) { // Verificar también si no es administrador (id 1)
    echo json_encode(['status' => 'error', 'message' => 'No tiene permisos para realizar esta acción.']);
    exit();
}

// Validar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idcuota = isset($_POST['idcuota']) ? intval($_POST['idcuota']) : 0;
    $clave = trim($_POST['password'] ?? '');

    // Validación de datos
    if (empty($idcuota) || empty($clave)) {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos. Por favor, intente de nuevo.']);
        exit();
    }

    // Validar la contraseña de administrador con MD5
    $sqlPassword = mysqli_query($conexion, "SELECT idusuario, clave, idrol FROM usuario 
                                            WHERE clave = '" . md5($clave) . "' AND idrol = 1");

    $user = mysqli_fetch_assoc($sqlPassword);

    // Verificar si existe un usuario administrador con la contraseña ingresada
    if (!$user || $user['idrol'] != 1) {
        echo json_encode(['status' => 'error', 'message' => 'Clave incorrecta o el usuario no tiene permisos de administrador.']);
        exit();
    }

    /*
    // Registrar auditoría (comentado para futuras actualizaciones)
    $logQuery = mysqli_query($conexion, "INSERT INTO auditoria (id_usuario, id_admin_autorizador, accion, fecha)
                                        VALUES ('$id_user', '{$user['idusuario']}', 'Actualizó cuota $idcuota', NOW())");

    if (!$logQuery) {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar la auditoría. Intente más tarde.']);
        exit();
    }
    */

    // Eliminar o actualizar la cuota
    $query_delete = mysqli_query($conexion, "UPDATE cuotas 
                                             SET condicion = 'PENDIENTE', 
                                                 fecha = '0000-00-00 00:00:00', 
                                                 total = '0' 
                                             WHERE idcuotas = '$idcuota'");

    if ($query_delete) {
        echo json_encode(['status' => 'success', 'message' => 'Pago actualizada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la pago. Intente más tarde.']);
    }
    exit();
}

// Si no es una solicitud POST válida
echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida.']);
exit();