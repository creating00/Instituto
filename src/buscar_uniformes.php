<?php
require("../conexion.php");

if (isset($_POST['buscar_uniforme'])) {  // Cambiamos 'mi_busqueda' por 'buscar_uniforme'
    echo '<table class="table table-hover">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Talla</th>
                <th hidden>Género</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acción</th>
            </tr>';

    $buscar_uniforme = $_POST['buscar_uniforme'];

    $query = "SELECT id_uniforme, nombre, talla, genero, precio, stock FROM uniformes 
              WHERE nombre LIKE '%$buscar_uniforme%' LIMIT 10";
    $resultados = mysqli_query($conexion, $query);

    while ($uniforme = mysqli_fetch_array($resultados)) {
        // Verificar si el stock es 0
        $disabled = $uniforme['stock'] == 0 ? 'disabled' : '';  // Si el stock es 0, deshabilitamos el botón

        echo "<tr>
            <td>{$uniforme['id_uniforme']}</td>
            <td>{$uniforme['nombre']}</td>
            <td>{$uniforme['talla']}</td>
            <td hidden>{$uniforme['genero']}</td>
            <td>$" . number_format((float)$uniforme['precio'], 2) . "</td>
            <td>{$uniforme['stock']}</td>
            <td>
                <button class='btn btn-primary' 
                        onclick='seleccionarUniforme({$uniforme['id_uniforme']}, \"{$uniforme['nombre']}\", \"{$uniforme['precio']}\", {$uniforme['stock']})'
                        {$disabled}>Seleccionar</button>
            </td>
          </tr>";
    }
    echo '</table>';
} else {
    echo '<p>No se encontraron resultados.</p>';
}
