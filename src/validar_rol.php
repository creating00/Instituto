<?php
session_start();
header('Content-Type: application/json');
include "../conexion.php";
include "RolesHandler.php"; // Asegúrate de incluir el archivo donde está la clase RolesHandler

// Verificar que el usuario esté autenticado
if (isset($_SESSION['idUser'])) {
    try {
        $rolesHandler = new RolesHandler($conexion);
        $userRole = $rolesHandler->validarRol($_SESSION['idUser']);

        // Retornar el rol en formato JSON
        echo json_encode(['role' => $userRole]);
    } catch (Exception $e) {
        // Manejar errores internos
        echo json_encode(['error' => 'Error al validar el rol: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Usuario no autenticado']);
}
?>