<?php
require "../conexion.php";
require_once 'emailService.php';

header('Content-Type: application/json');

// Recuperar datos enviados por el frontend
$data = json_decode(file_get_contents('php://input'), true);

// Validar si los datos necesarios fueron proporcionados
$idCurso = $data['idCurso'] ?? null;
$idAlumno = $data['alumnoId'] ?? null;
$email = $data['email'] ?? null;
$nombreCurso = $data['nombreCurso'] ?? null;

if (!$idCurso || !$idAlumno || !$email) {
    echo json_encode(["success" => false, "message" => "Faltan parámetros obligatorios."]);
    exit;
}

// Ruta del archivo PDF, se asegura que exista
$filePath = __DIR__ . "/../diplomas/cursos/{$idCurso}/{$idAlumno}/{$idCurso}_{$idAlumno}.pdf";

if (!file_exists($filePath)) {
    echo json_encode(["success" => false, "message" => "El archivo PDF no existe en el servidor."]);
    exit;
}

// Crear instancia del servicio de correo
$emailService = new EmailService();

try {
    // Asunto y cuerpo del correo
    $subject = "Diploma del curso ID: {$idCurso}";
    $body = "<p>Estimado/a {$data['nombreAlumno']} {$data['apellidoAlumno']},</p>
             <p>Adjunto encontrarás el diploma correspondiente al curso: <strong>{$nombreCurso}</strong>.</p>
             <p>Saludos cordiales,</p>
             <p>Academia Yolanda</p>";

    // Enviar el correo con el archivo adjunto
    $emailService->enviarCorreo($email, $subject, $body, [$filePath]);

    echo json_encode(["success" => true, "message" => "Correo enviado exitosamente."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al enviar el correo: " . $e->getMessage()]);
}
