<?php
class ProfesorBusqueda
{
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para buscar profesores según el nombre
    public function buscarProfesores($mi_busqueda_profesor, $sede)
    {
        // Obtener el ID de la sede si es necesario
        $idsede = $this->obtenerIdSede($sede);

        // Preparar la consulta dependiendo de la sede
        if ($sede == "GENERAL") {
            $query = "
            SELECT idprofesor, profesor.nombre, profesor.apellido, profesor.dni, sedes.nombre as sede
            FROM profesor
            INNER JOIN sedes ON profesor.idsede = sedes.idsede
            WHERE profesor.nombre LIKE '%$mi_busqueda_profesor%' AND profesor.estado = 1
            LIMIT 5
            ";
        } else {
            $query = "
            SELECT idprofesor, profesor.nombre, profesor.apellido, profesor.dni, sedes.nombre as sede
            FROM profesor
            INNER JOIN sedes ON profesor.idsede = sedes.idsede
            WHERE profesor.nombre LIKE '%$mi_busqueda_profesor%' AND profesor.estado = 1 AND profesor.idsede = '$idsede'
            LIMIT 5
            ";
        }

        return $this->ejecutarConsulta($query);
    }

    // Método para obtener el ID de la sede a partir de su nombre
    private function obtenerIdSede($sede)
    {
        $query = "SELECT idsede FROM sedes WHERE nombre = '$sede'";
        $resultado = $this->ejecutarConsulta($query);

        // Si se encuentra la sede, devolver el ID
        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_array($resultado);
            return $row['idsede'];
        } else {
            return null;  // Si no se encuentra la sede, retornar null
        }
    }

    // Método para ejecutar una consulta SQL genérica y devolver el resultado
    private function ejecutarConsulta($query)
    {
        $resultado = mysqli_query($this->conexion, $query);

        if (!$resultado) {
            die("Error en la consulta: " . mysqli_error($this->conexion));
        }

        return $resultado;
    }
}
