function registrarInscripcion() {
  console.log("Hola" + precioInscripcion);
  var parametros = {
    guardar: "1",
    dni: $("#dni").val(),
    curso: $("#curso").val(),
    sede: $("#sede").val(),
    precio: parseFloat($("#precio").val().replace(/,/g, "")), // Quita comas y convierte a número
    dniP: $("#dniP").val(),
    salas: $("#salas").val(),
    //total: parseFloat($("#total").val().replace(/,/g, "")), // Quita comas y convierte a número
    total: precioInscripcion, // Quita comas y convierte a número
    usuario: $("#usuario").val(),
    fechaComienzo: $("#fechaComienzo").val(),
    fechaTermino: $("#fechaTermino").val(),
    medioPago: $("#medioPago").val(),
    isDetalle: $("#detalleCheckbox").is(":checked"),
    detalle: $("#detalleTextarea").val(),
    nroFactura: $("#nroFactura").val(),
  };

  return $.ajax({
    url: "api_registrar_inscripcion.php",
    type: "POST",
    data: parametros,
    dataType: "json",
  });
}

function registrarVentaUniforme(idInscripcion) {
  let datosVenta = {
    id_uniforme: $("#id_uniforme").val(),
    id_alumno: $("#alumno_id").val(),
    cantidad: 1,
    precio_unitario: precioUniforme,
    total: precioUniforme,
    medio_pago: $("#medioPago").val(),
    numero_factura: $("#nroFactura").val(),
    vino_de_inscripcion: 1,
  };

  return $.ajax({
    url: "api_registrar_venta.php",
    type: "POST",
    data: JSON.stringify(datosVenta),
    contentType: "application/json",
    dataType: "json",
  })
    .done(function (respuesta) {
      console.log("Respuesta de la API:", respuesta);
      if (respuesta.status !== "success") {
        console.error("Error en la venta:", respuesta.message);
        alert("Error en la venta: " + respuesta.message);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.error(
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
    });
}

function actualizarFacturaYGenerarPDF(idinscripcion, tipoImpresion) {
  return $.ajax({
    url: "actualizarFactura.php",
    type: "POST",
    dataType: "json",
  }).done(function (respuesta) {
    if (respuesta.success) {
      $("#nroFactura").val(respuesta.nroFactura); // Actualizar el input

      var url;
      if (tipoImpresion === "A4") {
        url = "pdf/reporte_old.php?idInscripcion=" + idinscripcion;
      } else if (tipoImpresion === "Ticket") {
        url = "pdf/reporte.php?idInscripcion=" + idinscripcion;
      } else {
        console.error("Tipo de impresión no válido:", tipoImpresion);
        return;
      }
      window.open(url, "_blank"); // Abrir PDF en nueva pestaña
    } else {
      alert("Error al actualizar la factura: " + respuesta.error);
    }
  });
}

function mostrarModal(tipo, mensaje) {
  let modalHeader = $("#modalHeader");
  let modalTitle = $("#modalTitle");
  let modalMessage = $("#modalMessage");

  // Limpiar clases previas
  modalHeader.removeClass("bg-success bg-warning bg-danger");

  // Asignar clase según el tipo de mensaje
  if (tipo === "success") {
    modalHeader.addClass("bg-success");
    modalTitle.text("Éxito");
  } else if (tipo === "warning") {
    modalHeader.addClass("bg-warning");
    modalTitle.text("Advertencia");
  } else {
    modalHeader.addClass("bg-danger");
    modalTitle.text("Error");
  }

  // Insertar mensaje y mostrar el modal
  modalMessage.html(mensaje);
  $("#respuestaModal").modal("show");
}

function guardar() {
  let tipoImpresion = $("#tipo-impresion").val();
  let incluirUniforme = $("#uniformeCheckbox").prop("checked");
  let idUniforme = $("#id_uniforme").val();

  console.log("incluirUniforme:", incluirUniforme, "idUniforme:", idUniforme);

  if (
    incluirUniforme &&
    (idUniforme === null || idUniforme === "0" || idUniforme === "")
  ) {
    mostrarModal("danger", "Debes seleccionar un uniforme válido.");
    return;
  }

  registrarInscripcion()
    .done(function (respuesta) {
      if (respuesta.status === "success") {
        let idInscripcion = respuesta.idinscripcion;

        if (idInscripcion && idInscripcion !== "0") {
          if (incluirUniforme) {
            registrarVentaUniforme(idInscripcion).done(function (
              respuestaVenta
            ) {
              if (respuestaVenta.status === "success") {
                let idVenta = respuestaVenta.id_venta;
                actualizarStock(idUniforme, 1).done(function () {
                  if ($("#detalleCheckbox").is(":checked")) {
                    const detalle = $("#detalleTextarea").val();

                    actualizarDetalleFac(detalle)
                      .then(function () {
                        actualizarFacturaYGenerarPDF(
                          idInscripcion,
                          tipoImpresion
                        ).done(function () {
                          limpiar();
                          mostrarModal(
                            "success",
                            "Inscripción guardada con éxito."
                          );
                        });
                      })
                      .catch(function (error) {
                        console.error(error);
                        mostrarModal(
                          "error",
                          "Hubo un problema al actualizar el detalle."
                        );
                      });
                  } else {
                    actualizarFacturaYGenerarPDF(
                      idInscripcion,
                      tipoImpresion
                    ).done(function () {
                      limpiar();
                      mostrarModal(
                        "success",
                        "Inscripción guardada con éxito."
                      );
                    });
                  }
                });
              } else {
                mostrarModal(
                  "danger",
                  "Error en la venta: " + respuestaVenta.message
                );
              }
            });
          } else {
            actualizarFacturaYGenerarPDF(idInscripcion, tipoImpresion).done(
              function () {
                limpiar();
                mostrarModal("success", "Inscripción guardada con éxito.");
              }
            );
          }
        } else {
          mostrarModal("warning", "Error: idInscripcion no es válido.");
        }
      } else {
        mostrarModal("danger", "Error: " + respuesta.message);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      mostrarModal("danger", "Error al guardar la inscripción.");
    });
}

function actualizarStock(id_uniforme, cantidad) {
  return $.ajax({
    url: "api_actualizar_stock.php",
    type: "POST",
    data: {
      id_uniforme: id_uniforme,
      cantidad: 1,
    },
    success: function (respuesta) {
      // Verificamos si la respuesta es un objeto con la propiedad 'status'
      if (respuesta && respuesta.status !== "success") {
        mostrarModal("Error al actualizar el stock: " + respuesta.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error en AJAX:", textStatus, errorThrown); // Para ver qué pasa en caso de error
      alert("Error al comunicarme con el servidor para actualizar el stock.");
    },
  });
}

function obtenerUltimoId(tipoImpresion) {
  $.ajax({
    url: "ultimoIdInscripcion.php",
    type: "GET",
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.success) {
        // Obtener el último ID
        var lastId = respuesta.last_id;

        // Ahora construimos la URL con solo el último ID
        var url;
        if (tipoImpresion === "A4") {
          url = "pdf/reporte_old.php?idInscripcion=" + lastId;
        } else if (tipoImpresion === "Ticket") {
          url = "pdf/reporte.php?idInscripcion=" + lastId;
        } else {
          console.error("Tipo de impresión no válido:", tipoImpresion);
          return;
        }

        // Abrir la URL en una nueva ventana
        window.open(url, "_blank");
      } else {
        alert("Error al obtener el último ID: " + respuesta.message);
      }
    },
    error: function () {
      alert("Error al comunicarse con el servidor.");
    },
  });
}

function actualizarDetalleFac(nuevoDetalleFac) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "actualizar_detalle.php", // URL de la API de actualización
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        detalle_fac: nuevoDetalleFac,
      }),
      success: function (response) {
        resolve(response);
      },
      error: function (xhr, status, error) {
        reject("Error al actualizar el detalle_fac: " + error);
      },
    });
  });
}
function enviar_correo() {
  dni = $("#dni").val();
  curso = $("#curso").val();

  var parametros = {
    guardar: "1",
    dni: dni,
    curso: curso,
    precio: $("#precio").val(),
    dniP: $("#dniP").val(),
    //"curso" : $("#curso").val(),
    salas: $("#salas").val(),
    total: $("#total").val(),
    usuario: $("#usuario").val(),
    sede: $("#sede").val(),
    fechaComienzo: $("#fechaComienzo").val(),
    medioPago: $("#medioPago").val(),
  };
  $.ajax({
    data: parametros,
    url: "correoSMTP.php",
    type: "post",

    error: function () {
      alert("Error");
    },

    success: function (mensaje) {
      $(".resultados").html(mensaje);
      window.location.href = "inscripcion.php";
    },
  });
}

function limpiar() {
  $("#dni").val("");
  $("#fechaComienzo").val("");
  $("#fechaTermino").val("");
  $("#dniP").val("");
  $("#nombre").val("");
  $("#apellido").val("");
  $("#nombreP").val("");
  $("#apellidoP").val("");
  $("#curso").val("");
  $("#duracion").val("");
  $("#precio").val("");
  $("#total").val("");
  // Limpiar el checkbox y el textarea del detalle
  $("#detalleCheckbox").prop("checked", false); // Desmarca el checkbox
  $("#detalleTextarea").val("").hide(); // Limpia y oculta el textarea

  // Limpiar los campos de búsqueda de ambos modales
  $("#cuadro_buscar").val(""); // Limpiar campo de búsqueda en el modal de estudiantes
  $("#cuadro_buscar_uniforme").val(""); // Limpiar campo de búsqueda en el modal de uniformes

  // Si tienes algún modal abierto, lo puedes cerrar
  $("#miModal").modal("hide");
  $("#modalUniformes").modal("hide");
}
