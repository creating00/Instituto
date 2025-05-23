<?php
include "../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $talla = trim($_POST['talla']);
    $genero = trim($_POST['genero']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    if (empty($nombre) || empty($talla) || empty($genero) || $precio <= 0 || $stock < 0) {
        echo json_encode(["status" => "error", "message" => "Todos los campos obligatorios deben estar llenos."]);
        exit;
    }

    $query = "INSERT INTO uniformes (nombre, descripcion, talla, genero, precio, stock) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssssdi", $nombre, $descripcion, $talla, $genero, $precio, $stock);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Uniforme agregado correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al insertar el uniforme."]);
    }

    $stmt->close();
    $conexion->close();
}
?>
