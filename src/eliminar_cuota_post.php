<?php
session_start();
require("../conexion.php");
header('Content-Type: application/json'); // Indicar que se devuelve JSON

// Asegurarse de que el usuario esté logueado y tiene permisos
$id_user = $_SESSION['idUser'];
$permiso = "cobrar";

// Verificar permisos
$sql = mysqli_query($conexion, "SELECT 1 FROM permisos p 
                                INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
                                WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_assoc($sql); // Solo necesitamos una fila

// Si no existe el permiso y el usuario no es el admin (id_user != 1)
if (!$existe && $id_user != 1) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permisos para realizar esta acción.']);
    exit();
}

// Verificar si el id de la cuota fue enviado via POST
if (!empty($_POST['idcuota'])) {
    $idcuotas = intval($_POST['idcuota']); // Validar que es un número entero

    // Actualizar la cuota (ponerla en PENDIENTE)
    $query_delete = mysqli_query($conexion, "UPDATE cuotas 
                                             SET condicion = 'PENDIENTE', 
                                                 fecha = '0000-00-00 00:00:00', 
                                                 total = '0' 
                                             WHERE idcuotas = $idcuotas");

    // Comprobar si la actualización fue exitosa
    if ($query_delete) {
        echo json_encode(['status' => 'success', 'message' => 'Pago actualizada correctamente.']);
    } else {
        // Retornar error de MySQL si la actualización falla
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la cuota. Intente más tarde.']);
    }
    exit(); // Asegurarse de salir después de la respuesta
}

// Si no se proporcionó el ID de la cuota
echo json_encode(['status' => 'error', 'message' => 'ID de cuota no proporcionado.']);
