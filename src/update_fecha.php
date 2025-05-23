<?php
// Conexion a la base de datos
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados
    $idCurso = $_POST['idCurso'];
    $fechaTermino = $_POST['fechaTermino'];
    $fechaComienzo = $_POST['fechaComienzo'];

    // Array para acumular mensajes de respuesta
    $response = [
        'success' => false, // Éxito general de las operaciones
        'messages' => []    // Mensajes detallados para cada paso
    ];

    // Empezamos la transacción para asegurar la consistencia
    $conexion->begin_transaction();

    try {
        // 1. Actualizar fechaTermino en la tabla curso
        $queryCurso = "UPDATE curso SET fechaTermino = ? WHERE idcurso = ?";
        $stmtCurso = $conexion->prepare($queryCurso);
        $stmtCurso->bind_param('si', $fechaTermino, $idCurso);

        if ($stmtCurso->execute()) {
            $response['messages'][] = "Fecha de término actualizada en la tabla curso.";
        } else {
            throw new Exception("Error al actualizar curso: " . $stmtCurso->error);
        }

        // 2. Actualizar fechaTermino en la tabla inscripcion
        $queryInscripcion = "UPDATE inscripcion SET fechaTermino = ? WHERE idcurso = ? AND fechacomienzo = ?";
        $stmtInscripcion = $conexion->prepare($queryInscripcion);
        $stmtInscripcion->bind_param('sis', $fechaTermino, $idCurso, $fechaComienzo);

        if ($stmtInscripcion->execute()) {
            $response['messages'][] = "Fecha de término actualizada en la tabla inscripción.";
        } else {
            throw new Exception("Error al actualizar inscripción: " . $stmtInscripcion->error);
        }

        // Commit de la transacción si todo fue exitoso
        $conexion->commit();
        $response['success'] = true;
    } catch (Exception $e) {
        // Rollback en caso de error
        $conexion->rollback();
        $response['messages'][] = "Transacción revertida por error.";
        $response['error'] = $e->getMessage();
    }

    // Enviar respuesta en formato JSON
    echo json_encode($response);
    exit;
}
