<?php
require_once '../conexion.php';
require_once 'CuotasHandler.php';

$idAlumno = $_GET['idAlumno'] ?? null;

if ($idAlumno) {
    $cuotasHandler = new CuotasHandler($conexion);
    $cuotasPendientes = $cuotasHandler->verificarCuotasPendientes($idAlumno);

    echo json_encode(["cuotasPendientes" => $cuotasPendientes]);
} else {
    echo json_encode(["error" => "ID de alumno no proporcionado."]);
}
