<?php
require "../conexion.php";

$response = ['exists' => false];

$data = json_decode(file_get_contents('php://input'), true);
$idCurso = $data['idCurso'] ?? null;
$idAlumno = $data['idAlumno'] ?? null;

if (!$idCurso || !$idAlumno) {
    echo json_encode($response);
    exit;
}

$alumnoDir = __DIR__ . "/../diplomas/cursos/{$idCurso}/{$idAlumno}";
$newFileName = "{$idCurso}_{$idAlumno}.pdf";
$filePath = $alumnoDir . '/' . $newFileName;

// Verificar si el archivo ya existe
if (file_exists($filePath)) {
    $response['exists'] = true;
}

echo json_encode($response);
