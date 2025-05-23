<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

session_start();

// Conectar a la base de datos
require_once("../conexion.php"); // Este archivo debe definir la variable $conexion

$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos) {
    echo json_encode(["status" => "error", "message" => "Datos no válidos."]);
    exit;
}

// Capturar datos
$id_uniforme = $datos["id_uniforme"] ?? null;
$id_alumno = $datos["id_alumno"] ?? null;
$cantidad = $datos["cantidad"] ?? 1;
$total = $datos["total"] ?? 0;
$medio_pago = $datos["medio_pago"] ?? "Efectivo";
$numero_factura = $datos["numero_factura"] ?? null;
$precio_unitario = $datos["precio_unitario"] ?? 0;
$viene_de_inscripcion = $datos["vino_de_inscripcion"] ?? false;
$id_usuario = $_SESSION['idUser'] ?? null; // Tomamos el usuario de la sesión
$fecha_venta = date("Y-m-d H:i:s");

// Validar datos
if (!$id_uniforme || !$id_alumno || $cantidad < 1 || $total < 0 || !$id_usuario) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos o incorrectos."]);
    exit;
}

if (!is_numeric($id_uniforme) || $id_uniforme <= 0) {
    echo json_encode(["status" => "error", "message" => "ID de uniforme inválido."]);
    exit;
}

// Insertar en la base de datos
$sql = "INSERT INTO ventas_uniformes (id_uniforme, id_alumno, cantidad, total, fecha_venta, medio_pago, precio_unitario, id_usuario, numero_factura, vino_de_inscripcion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiidssdssi", $id_uniforme, $id_alumno, $cantidad, $total, $fecha_venta, $medio_pago, $precio_unitario, $id_usuario, $numero_factura, $viene_de_inscripcion);

if ($stmt->execute()) {
    $id_venta = $conexion->insert_id; // Obtener el ID de la venta insertada
    echo json_encode(["status" => "success", "message" => "Venta registrada correctamente.", "id_venta" => $id_venta]); 
} else {
    echo json_encode(["status" => "error", "message" => "Error al registrar la venta: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
