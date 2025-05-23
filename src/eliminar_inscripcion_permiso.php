<?php
session_start();
require("../conexion.php");

if (!isset($_SESSION['idUser'])) {
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['idUser'];
$permiso = "inscripcion";

// Verificamos el permiso del usuario
$id_user = mysqli_real_escape_string($conexion, $id_user);
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p 
    INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
    WHERE d.id_usuario = '$id_user' AND p.nombre = '$permiso'");

$existe = mysqli_fetch_all($sql);

// Si no tiene permisos y no es admin (id = 1), redirigir
if (empty($existe) && $id_user != 1) {
    $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
    header("Location: inscripcion.php");
    exit();
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos de entrada
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $idinscripcion = isset($_POST['idinscripcion']) ? intval($_POST['idinscripcion']) : 0;

    if (empty($password) || $idinscripcion <= 0) {
        $_SESSION['error'] = "Datos inválidos. Por favor, intente de nuevo.";
        header("Location: inscripcion.php");
        exit();
    }

    // Verificar la contraseña del administrador
    $sqlPassword = mysqli_query($conexion, "SELECT password FROM usuario WHERE idusuario = '$id_user' AND idrol = 1");
    $user = mysqli_fetch_assoc($sqlPassword);

    if ($user && password_verify($password, $user['password'])) {
        // La contraseña es válida, intentar eliminar la inscripción
        $query_delete = mysqli_query($conexion, "DELETE FROM inscripcion WHERE idinscripcion = '$idinscripcion'");

        if ($query_delete) {
            $_SESSION['success'] = "Inscripción eliminada correctamente.";
            header("Location: inscripcion.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al eliminar la inscripción. Intente más tarde.";
            header("Location: inscripcion.php");
            exit();
        }
    } else {
        // Contraseña incorrecta
        $_SESSION['error'] = "Contraseña incorrecta.";
        header("Location: inscripcion.php");
        exit();
    }
} else {
    // Redirigir si no es una solicitud POST
    $_SESSION['error'] = "Solicitud inválida.";
    header("Location: inscripcion.php");
    exit();
}
