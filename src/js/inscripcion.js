var idInscripcionGlobal = null; // Variable global

function buscar_datos_profesor() {
  dniP = $("#dniP").val();
  sedeP = $("#sede").val();

  var parametros = {
    buscar_profesor: "1",
    dniP: dniP,
    sedeP: sedeP,
  };
  $.ajax({
    data: parametros,
    dataType: "json",
    url: "datosInscripcion.php",
    type: "POST",

    error: function () {
      alert("Error");
    },

    success: function (valores) {
      $("#nombreP").val(valores.nombreP);
      $("#apellidoP").val(valores.apellidoP);
    },
  });
}

function capturarIdInscripcion(element) {
  // Captura el valor del atributo data-idinscripcion
  idInscripcionGlobal = $(element).data("idinscripcion");
  console.log("ID Inscripción capturado:", idInscripcionGlobal); // Verifica en la consola
}

function generarImpresion() {
  // Verificamos si el idInscripcion está llegando correctamente
  console.log("Generando impresión con ID Inscripción:", idInscripcionGlobal);

  // Obtener el tipo de impresión seleccionado
  const tipoImpresion = document.getElementById("tipo-impresion-modal").value;

  // Verificamos el tipo de impresión seleccionado
  console.log("Tipo de impresión seleccionado:", tipoImpresion);

  let url;

  // Determinar la URL basada en el tipo de impresión
  if (tipoImpresion === "A4") {
    console.log("Tipo de impresión seleccionado: A4");
    url = "pdf/reporte_old.php?idInscripcion=" + idInscripcionGlobal;
  } else if (tipoImpresion === "Ticket") {
    console.log("Tipo de impresión seleccionado: Ticket");
    url = "pdf/reporte.php?idInscripcion=" + idInscripcionGlobal;
  } else {
    console.error("Tipo de impresión no válido:", tipoImpresion);
    return; // Salir si no es válido
  }

  // Verificamos la URL generada
  console.log("URL generada:", url);

  // Abrir la URL generada en una nueva pestaña
  window.open(url, "_blank");

  // Cerrar el modal
  $("#impresionModal").modal("hide");
}
