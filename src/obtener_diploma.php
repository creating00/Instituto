<?php
require "../conexion.php";

$idCurso = $_GET['idCurso'] ?? null;
$idAlumno = $_GET['idAlumno'] ?? null;

if (!$idCurso || !$idAlumno) {
    http_response_code(400); // Código de error: Bad Request
    echo "Faltan parámetros necesarios.";
    exit;
}

$filePath = __DIR__ . "/../diplomas/cursos/{$idCurso}/{$idAlumno}/{$idCurso}_{$idAlumno}.pdf";

if (!file_exists($filePath)) {
    http_response_code(404); // Código de error: Not Found
    echo "Archivo no encontrado.";
    exit;
}

// Configuración para enviar el archivo PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
header('Content-Length: ' . filesize($filePath));

// Leer y enviar el archivo
readfile($filePath);
exit;
