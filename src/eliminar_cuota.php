<?php
require("../conexion.php");
session_start();

$id_user = $_SESSION['idUser'] ?? 0;
$permiso = "cuotas";

$stmt = $conexion->prepare("
    SELECT p.*, d.* 
    FROM permisos p 
    INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
    WHERE d.id_usuario = ? AND p.nombre = ?
");
$stmt->bind_param("is", $id_user, $permiso);
$stmt->execute();
$result = $stmt->get_result();
$existe = $result->fetch_all(MYSQLI_ASSOC);

// Redirige si no tiene permiso y no es el usuario 1
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
    exit();
}

if (!empty($_GET['id'])) {
    $idcuotas = intval($_GET['id']); // Asegura que sea un número entero

    // Consulta preparada
    $stmt = $conexion->prepare("
        UPDATE cuotas 
        SET condicion = 'PENDIENTE', 
            fecha = '0000-00-00 00:00:00', 
            total = 0 
        WHERE idcuotas = ?
    ");

    if ($stmt) {
        $stmt->bind_param("i", $idcuotas);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    mysqli_close($conexion);
    header("Location: cuotas.php");
    exit();
}
?>