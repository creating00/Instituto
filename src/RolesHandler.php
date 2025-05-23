<?php
class RolesHandler
{
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para insertar los roles "admin" y "secretaria" si no existen
    public function inicializarRoles()
    {
        // Roles predefinidos
        $roles = [
            ['idrol' => 1, 'nombrerol' => 'admin'],
            ['idrol' => 2, 'nombrerol' => 'secretaria']
        ];

        foreach ($roles as $rol) {
            // Escapar los valores para evitar inyección SQL
            $idrol = mysqli_real_escape_string($this->conexion, $rol['idrol']);
            $nombrerol = mysqli_real_escape_string($this->conexion, $rol['nombrerol']);

            // Verificar si el rol ya existe
            $queryCheck = "SELECT COUNT(*) as total FROM roles WHERE idrol = '$idrol'";
            $resultadoCheck = mysqli_query($this->conexion, $queryCheck);

            if (!$resultadoCheck) {
                die("Error en la consulta: " . mysqli_error($this->conexion));
            }

            $fila = mysqli_fetch_assoc($resultadoCheck);

            // En caso que el rol no exista, sera insertado
            if ($fila['total'] == 0) {
                $queryInsert = "INSERT INTO roles (idrol, nombrerol) VALUES ('$idrol', '$nombrerol')";
                $resultadoInsert = mysqli_query($this->conexion, $queryInsert);

                if (!$resultadoInsert) {
                    die("Error al insertar el rol $nombrerol: " . mysqli_error($this->conexion));
                }

                //echo "Rol '$nombrerol' insertado correctamente.<br>";
            } else {
                // echo "El rol '$nombrerol' ya existe.<br>";
            }
        }
    }
    public function validarRol($userId)
    {
        $query = "SELECT r.nombrerol FROM roles r JOIN usuario u ON u.idrol = r.idrol WHERE u.idusuario = '$userId'";
        $resultado = mysqli_query($this->conexion, $query);

        if (!$resultado) {
            die("Error en la consulta: " . mysqli_error($this->conexion));
        }

        $fila = mysqli_fetch_assoc($resultado);
        return $fila ? $fila['nombrerol'] : null;
    }
}
