<?php
require("../conexion.php");

// Asegura que la sesión esté iniciada
session_start();

$id_user = $_SESSION['idUser'] ?? 0;
$permiso = "producto";

// Consulta preparada para verificar permisos
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

// Si se recibe un ID por GET, desactiva el usuario
if (!empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consulta preparada para actualizar el estado
    $stmt = $conexion->prepare("UPDATE producto SET estado = 0 WHERE codproducto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    mysqli_close($conexion);
    header("Location: usuarios.php");
    exit();
}
?>


