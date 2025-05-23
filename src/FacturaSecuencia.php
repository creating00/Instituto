<?php

class FacturaSecuencia
{
    private $conexion; // Conexión a la base de datos

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para inicializar la secuencia del año actual
    public function inicializarSecuencia()
    {
        $anioActual = date("Y");

        // Verificar si ya existe el registro del año actual
        $query = "SELECT * FROM facturacion_secuencia WHERE anio = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $anioActual);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            // Crear el registro con secuencia en 1
            $insertQuery = "INSERT INTO facturacion_secuencia (anio, secuencia) VALUES (?, 1)";
            $stmtInsert = $this->conexion->prepare($insertQuery);
            $stmtInsert->bind_param("i", $anioActual);
            $stmtInsert->execute();
        }
    }

    // Método para obtener la última secuencia del año actual
    public function obtenerUltimaSecuencia()
    {
        $anioActual = date("Y");

        // Consultar la última secuencia del año actual
        $query = "SELECT secuencia FROM facturacion_secuencia WHERE anio = ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $anioActual);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            return $fila['secuencia'];
        } else {
            // Si no existe, inicializar en 1
            $this->inicializarSecuencia();
            return 1;
        }
    }

    // Método para incrementar la secuencia
    public function incrementarSecuencia()
    {
        $anioActual = date("Y");

        // Obtener la última secuencia y actualizarla
        $ultimaSecuencia = $this->obtenerUltimaSecuencia();
        $nuevaSecuencia = $ultimaSecuencia + 1;

        // Actualizar la secuencia en la base de datos
        $updateQuery = "UPDATE facturacion_secuencia SET secuencia = ? WHERE anio = ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->conexion->prepare($updateQuery);
        $stmt->bind_param("ii", $nuevaSecuencia, $anioActual);
        $stmt->execute();

        return $nuevaSecuencia;
    }

    // Método para formatear la factura
    public function formatearFactura()
    {
        $anioActual = date("Y");

        // Obtener la última secuencia actualizada
        $ultimaSecuencia = $this->obtenerUltimaSecuencia();

        // Formatear como AÑO-00000000000001
        return sprintf("%d-%'.014d", $anioActual, $ultimaSecuencia);
    }

    public function formatearFacturaConMes()
    {
        $anioActual = date("y"); // Obtener los últimos dos dígitos del año
        $mesActual = date("m");  // Obtener el mes actual en formato numérico (01, 02, ..., 12)

        // Obtener la última secuencia actualizada
        $ultimaSecuencia = $this->obtenerUltimaSecuencia();

        // Formatear como AA-MM-0001
        return sprintf("%d-%s-%'.04d", $anioActual, $mesActual, $ultimaSecuencia);
    }

    public function formatearFacturaConMesAnterior()
    {
        $anioActual = date("Y"); // Obtener el año actual
        $mesActual = date("m");  // Obtener el mes actual en formato numérico (01, 02, ..., 12)

        // Obtener la última secuencia actualizada
        $ultimaSecuencia = $this->obtenerUltimaSecuencia();

        // Formatear como AÑO-Mes-000001
        return sprintf("%d-%s-%'.06d", $anioActual, $mesActual, $ultimaSecuencia);
    }
}
