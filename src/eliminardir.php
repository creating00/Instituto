<?php
function eliminarDirectorio($dir)
{
    // Verificar si el directorio existe
    if (!is_dir($dir)) {
        echo "El directorio no existe o no es válido.";
        return false;
    }

    // Obtener todos los archivos y subdirectorios dentro del directorio
    $archivos = array_diff(scandir($dir), array('.', '..'));

    foreach ($archivos as $archivo) {
        $ruta = $dir . DIRECTORY_SEPARATOR . $archivo;

        // Si es un directorio, llamar recursivamente
        if (is_dir($ruta)) {
            eliminarDirectorio($ruta);
        } else {
            // Si es un archivo, eliminarlo
            unlink($ruta);
        }
    }

    // Finalmente, eliminar el directorio vacío
    rmdir($dir);
    echo "El directorio y su contenido han sido eliminados exitosamente.";
    return true;
}

// Ruta del directorio a eliminar
$directorio = "../diplomas/cursos"; // Cambia esto a la ruta que desees eliminar

// Llamar a la función para eliminar el directorio
eliminarDirectorio($directorio);
