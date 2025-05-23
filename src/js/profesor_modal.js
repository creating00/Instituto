function mi_busqueda_profesor_mejorado() {
  var mi_busqueda_profesor = document.getElementById(
    "cuadro_buscar_profesor"
  ).value;

  $.ajax({
    url: "buscar_profesores.php", // El archivo que manejará la búsqueda de profesores
    method: "POST",
    data: {
      mi_busqueda_profesor: mi_busqueda_profesor,
      sede: "GENERAL",
    },
    success: function (response) {
      $("#tablaProfesor tbody").html(response); // Actualiza la tabla con los resultados
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}
function seleccionarProfesor(idprofesor) {
  $.ajax({
    url: "obtener_profesor.php", // El archivo que devolverá los detalles del profesor seleccionado
    method: "POST",
    data: { idprofesor: idprofesor },
    success: function (response) {
      //console.log(response);
      var profesor = JSON.parse(response);

      // Asignar los valores a los campos
      document.getElementById("nombreProfesor").value =
        profesor.nombre + " " + profesor.apellido;
      document.getElementById("dni").value = profesor.dni;

      //console.log(profesor.montoAPagar);

      // Asignar el id al campo oculto
      document.getElementById("idProfesor").value = profesor.idprofesor;

      // Verificar si el campo "montoAPagar" existe en el DOM antes de intentar modificarlo
      var montoAPagarElement = document.getElementById("montoAPagar");
      if (montoAPagarElement) {
        // Si existe, proceder con las modificaciones
        montoAPagarElement.classList.remove("d-none");

        // Mostrar la etiqueta correspondiente si el campo "montoAPagar" está presente
        montoAPagarElement.previousElementSibling.classList.remove("d-none");
      }

      // Mostrar los campos, excepto el id (que puede permanecer oculto)
      document.getElementById("dni").classList.remove("d-none");
      document.getElementById("nombreProfesor").classList.remove("d-none");

      document
        .getElementById("dni")
        .previousElementSibling.classList.remove("d-none");
      document
        .getElementById("nombreProfesor")
        .previousElementSibling.classList.remove("d-none");

      // Cerrar el modal después de seleccionar
      $("#modalProfesor").modal("hide");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}
