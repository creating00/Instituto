let timeout; // Variable para almacenar el temporizador

function buscarUniforme() {
  // Detenemos el temporizador si ya existe
  clearTimeout(timeout);

  // Creamos un nuevo temporizador que espere 500 ms (puedes ajustarlo a tu gusto)
  timeout = setTimeout(function () {
    var buscar = document.getElementById("cuadro_buscar_uniforme").value;
    var parametros = {
      buscar_uniforme: buscar, // Cambiamos 'mi_busqueda' a 'buscar_uniforme'
      accion: "buscar_uniformes",
    };

    $.ajax({
      data: parametros,
      url: "buscar_uniformes.php",
      type: "POST",
      beforeSend: function () {
        $("#mostrar_uniformes").html("Buscando...");
      },
      success: function (respuesta) {
        $("#mostrar_uniformes").html(respuesta);
      },
      error: function (xhr, status, error) {
        console.log("Error: " + error); // Para ver si hay algún error en la solicitud
      },
    });
  }, 500); // Retardo de 500 ms
}

function seleccionarUniforme(id, nombre, precio, stock) {
  // Eliminar el signo $ del precio
  var precioSinSimbolo = precio.replace("$", "").trim();

  $("#uniformeSeleccionado").val(nombre);
  $("#id_uniforme").val(id);
  $("#precioUniforme").val(precioSinSimbolo);
  $("#stockDisponible").val(stock);
  $("#modalUniformes").modal("hide");

  validarCantidadConStock();
  actualizarTotal();
}

function limpiar() {
  // Limpiar campos de texto
  $("#dni").val(""); // Cédula
  $("#nombre").val(""); // Nombre
  $("#apellido").val(""); // Apellido
  $("#uniformeSeleccionado").val(""); // Uniforme seleccionado
  $("#id_uniforme").val(""); // ID del uniforme
  $("#precioUniforme").val(""); // Precio del uniforme
  $("input[name='cantidad']").val("1"); // Cantidad
  $("select[name='medio_pago']").val("Efectivo"); // Medio de pago (restaurar a 'Efectivo' por defecto)
  $("#stockDisponible").val(""); // Stock disponible

  // Limpiar el input oculto de alumno_id
  $("#alumno_id").val(""); // Alumno ID

  // Limpiar cualquier estado visual (por ejemplo, los errores si es necesario)
  $(".is-invalid").removeClass("is-invalid");
  $(".is-valid").removeClass("is-valid");

  // Limpiar los campos de búsqueda de ambos modales
  $("#cuadro_buscar").val(""); // Limpiar campo de búsqueda en el modal de estudiantes
  $("#cuadro_buscar_uniforme").val(""); // Limpiar campo de búsqueda en el modal de uniformes

  // Limpiar el contenido de los divs que contienen las tablas
  $("#mostrar_uniformes").html(""); // Limpiar tabla de uniformes
  $("#mostrar_mensaje").html(""); // Limpiar mensaje de búsqueda de estudiantes

  $("totalVenta").val("0.00");

  // Si tienes algún modal abierto, lo puedes cerrar
  $("#miModal").modal("hide");
  $("#modalUniformes").modal("hide");
}

function actualizarStock(id_uniforme, cantidad) {
  return $.ajax({
    url: "api_actualizar_stock.php",
    type: "POST",
    data: {
      id_uniforme: id_uniforme,
      cantidad: cantidad,
    },
    success: function (respuesta) {
      console.log("Respuesta recibida:", respuesta); // Añadimos este log para ver lo que devuelve el servidor

      // Verificamos si la respuesta es un objeto con la propiedad 'status'
      if (respuesta && respuesta.status !== "success") {
        alert("Error al actualizar el stock: " + respuesta.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error en AJAX:", textStatus, errorThrown); // Para ver qué pasa en caso de error
      alert("Error al comunicarme con el servidor para actualizar el stock.");
    },
  });
}

// Función para validar la cantidad basada en el stock
function validarCantidadConStock() {
  var stock = $("#stockDisponible").val(); // Obtener el valor del stock
  var cantidad = $("input[name='cantidad']").val(); // Obtener la cantidad actual

  // Convertir stock a número (si está vacío, tratarlo como 0)
  stock = stock === "" ? 0 : parseInt(stock, 10);

  // Asegurarse de que la cantidad no exceda el stock
  if (stock > 0) {
    if (cantidad === "" || parseInt(cantidad, 10) < 1) {
      $("input[name='cantidad']").val(1); // Establecer mínimo a 1
    } else if (parseInt(cantidad, 10) > stock) {
      $("input[name='cantidad']").val(stock); // Limitar cantidad al stock
    }
  } else {
    // Si el stock está vacío, permitir solo valores mínimos (1)
    if (cantidad === "" || parseInt(cantidad, 10) < 1) {
      $("input[name='cantidad']").val(1);
    }
  }
}

// Función para calcular y mostrar el total
function actualizarTotal() {
  var cantidad = parseInt($("input[name='cantidad']").val()) || 0;
  var precio = parseFloat($("#precioUniforme").val()) || 0;
  var total = cantidad * precio;

  // Formatear número en formato de miles con dos decimales
  var totalFormateado = total.toLocaleString("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });

  // Mostrar el total en el input correspondiente
  $("#totalVenta").val(totalFormateado);
}

// Evento de entrada en el campo de texto
document
  .getElementById("cuadro_buscar_uniforme")
  .addEventListener("input", buscarUniforme);

$(document).ready(function () {
  // Asignar el valor 1 por defecto al campo de cantidad
  $("input[name='cantidad']").val(1);

  // Evitar que se ingresen letras o caracteres especiales
  $("input[name='cantidad']").on("keydown", function (e) {
    // Permitir solo números, teclas de control (como backspace, delete, flechas) y teclas de navegación
    if (
      !(
        (
          (e.keyCode >= 48 && e.keyCode <= 57) || // Números del teclado principal
          (e.keyCode >= 96 && e.keyCode <= 105) || // Números del teclado numérico
          e.keyCode === 8 || // Backspace
          e.keyCode === 46 || // Delete
          e.keyCode === 37 || // Flecha izquierda
          e.keyCode === 39 || // Flecha derecha
          e.keyCode === 9 || // Tab
          e.keyCode === 13
        ) // Enter
      )
    ) {
      e.preventDefault(); // Bloquear la entrada si no es válida
    }
  });

  $("input[name='cantidad'], #precioUniforme").on("input", function () {
    actualizarTotal();
  });

  // Validar el contenido del campo en tiempo real
  $("input[name='cantidad']").on("input", function () {
    var valor = $(this).val();

    // Filtrar solo los números, eliminando cualquier letra o carácter no numérico
    var valorFiltrado = valor.replace(/[^0-9]/g, "");

    // Si el valor ha cambiado (es decir, si el usuario intentó escribir algo no numérico)
    if (valor !== valorFiltrado) {
      $(this).val(valorFiltrado); // Restablecer al valor filtrado (sin letras ni caracteres especiales)
    }

    // Validar la cantidad con respecto al stock
    validarCantidadConStock();
  });

  $("#precioUniforme").on("change", function () {
    $(this).trigger("input");
  });

  // Detectar cambios en el stock disponible
  $("#stockDisponible").on("input", function () {
    var stock = $(this).val(); // Obtener el valor del stock

    // Si el stock cambia de vacío a un valor numérico, reiniciar la cantidad a 1
    if (stock !== "") {
      $("input[name='cantidad']").val(1); // Reiniciar cantidad a 1
    }

    // Validar la cantidad con respecto al stock
    validarCantidadConStock();
  });
});

$(document).ready(function () {
  $("button[type='submit']").click(function (e) {
    e.preventDefault(); // Evita el envío tradicional del formulario

    let id_uniforme = parseInt($("input[name='id_uniforme']").val(), 10);
    let id_alumno = $("#alumno_id").val();
    let cantidad = $("input[name='cantidad']").val();
    let precio_unitario =
      parseFloat($("#precioUniforme").val().replace(",", "")) || 0;
    let medio_pago = $("select[name='medio_pago']").val();
    let numero_factura = $("#nroFactura").val(); // Tomamos el número de factura
    var tipoImpresion = $("select[name='tipo-impresion']").val();

    // Validaciones
    if (!id_alumno) {
      alert("Por favor, seleccione un alumno.");
      return;
    }
    if (!id_uniforme) {
      alert("Por favor, seleccione un uniforme.");
      return;
    }
    if (cantidad < 1 || isNaN(cantidad)) {
      alert("La cantidad debe ser un número válido mayor a 0.");
      return;
    }
    if (precio_unitario <= 0) {
      alert("El precio del uniforme no es válido.");
      return;
    }

    let total = (cantidad * precio_unitario).toFixed(2);

    let datos = {
      id_uniforme: id_uniforme,
      id_alumno: id_alumno,
      cantidad: cantidad,
      precio_unitario: precio_unitario,
      total: total,
      medio_pago: medio_pago,
      numero_factura: numero_factura,
      vino_de_inscripcion: 0,
    };

    // Llamada para registrar la venta
    $.ajax({
      url: "api_registrar_venta.php",
      type: "POST",
      data: JSON.stringify(datos),
      contentType: "application/json",
      success: function (respuesta) {
        if (respuesta.status === "success") {
          let idVenta = respuesta.id_venta;
          alert("Venta registrada correctamente.");

          // Actualizar el stock
          actualizarStock(id_uniforme, cantidad).done(function () {
            // Actualización de la factura
            $.ajax({
              url: "actualizarFactura.php",
              type: "post",
              dataType: "json",
              success: function (respuesta) {
                if (respuesta.success) {
                  // Actualizar el input de N° de Factura con el nuevo número
                  $("#nroFactura").val(respuesta.nroFactura);

                  // Generar PDF
                  var url;
                  if (tipoImpresion === "A4") {
                    url = "pdf/ventaUniformeA4.php?idVenta=" + idVenta;
                  } else if (tipoImpresion === "Ticket") {
                    url = "pdf/ventaUniforme.php?idVenta=" + idVenta;
                  } else {
                    console.error(
                      "Tipo de impresión no válido:",
                      tipoImpresion
                    );
                    return;
                  }

                  // Abrir la URL en una nueva ventana
                  window.open(url, "_blank");

                  limpiar(); // Limpiar campos después de guardar
                } else {
                  alert("Error al actualizar la factura: " + respuesta.error);
                }
              },
              error: function () {
                alert(
                  "Error al comunicarse con el servidor para actualizar la factura."
                );
              },
            });
          });
        } else {
          alert("Error: " + respuesta.message);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(
          "Error en AJAX:",
          textStatus,
          errorThrown,
          jqXHR.responseText
        );
        alert(
          "Error en AJAX: " +
            textStatus +
            " - " +
            errorThrown +
            "\n\n" +
            jqXHR.responseText
        );
      },
    });
  });
});
