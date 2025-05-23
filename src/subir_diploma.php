<?php
require "../conexion.php";

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCurso = $_POST['idCurso'] ?? null;
    $idAlumno = $_POST['idAlumno'] ?? null;

    if (!$idCurso || !$idAlumno) {
        $response['message'] = 'Faltan parámetros necesarios.';
        echo json_encode($response);
        exit;
    }

    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error al cargar el archivo.';
        echo json_encode($response);
        exit;
    }

    $fileTmpPath = $_FILES['pdf']['tmp_name'];
    $fileExtension = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));

    if ($fileExtension !== 'pdf') {
        $response['message'] = 'Solo se permiten archivos PDF.';
        echo json_encode($response);
        exit;
    }

    $baseDir = __DIR__ . '/../diplomas/cursos/' . $idCurso;
    $alumnoDir = $baseDir . '/' . $idAlumno;

    if (!file_exists($alumnoDir)) {
        if (!mkdir($alumnoDir, 0777, true)) {
            $response['message'] = 'Error al crear el directorio.';
            echo json_encode($response);
            exit;
        }
    }

    $newFileName = "{$idCurso}_{$idAlumno}.pdf";
    $destinationPath = $alumnoDir . '/' . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destinationPath)) {
        $response['success'] = true;
        $response['message'] = 'Archivo subido con éxito.';
    } else {
        $response['message'] = 'Error al mover el archivo.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
