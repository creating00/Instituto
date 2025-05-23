<?php
// MensajeRecordatorio.php
require_once "../conexion.php"; // Asegúrate de que la conexión esté correctamente incluida

class MensajeRecordatorio
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para obtener el mensaje en formato JSON
    public function obtenerMensajeJson()
    {
        $contenidoMensaje = $this->obtenerMensaje();  // Obtener el mensaje desde la base de datos
        if ($contenidoMensaje === null) {
            return json_encode(['error' => 'No se encontró el mensaje.']);
        }

        return json_encode(['contenidoMensaje' => $contenidoMensaje]);
    }

    // Método para obtener el mensaje como texto
    public function obtenerMensajeTexto()
    {
        $contenidoMensaje = $this->obtenerMensaje();  // Obtener el mensaje desde la base de datos
        if ($contenidoMensaje === null) {
            return 'Error: No se encontró el mensaje.';
        }

        return $contenidoMensaje;
    }

    // Método privado que consulta el mensaje desde la base de datos
    private function obtenerMensaje()
    {
        $query = mysqli_query($this->conexion, "SELECT contenidoMensaje FROM mensaje_recordatorio WHERE id = 1");
        $mensaje = mysqli_fetch_assoc($query);
        return $mensaje ? $mensaje['contenidoMensaje'] : null;
    }
}
