function mi_busqueda_curso_mejorado() {
  var mi_busqueda_curso = document.getElementById("cuadro_buscar_curso").value;

  $.ajax({
    url: "buscar_cursos.php",
    method: "POST",
    data: {
      mi_busqueda_curso: mi_busqueda_curso,
      sede: "GENERAL",
    },
    success: function (response) {
      $("#tablaCurso tbody").html(response);
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}

function mi_busqueda_curso_profesor() {
  var mi_busqueda_curso = document.getElementById(
    "cuadro_buscar_curso_profesor"
  ).value;
  var idprofesor = document.getElementById("idProfesor").value;

  console.log(idprofesor);

  $.ajax({
    url: "buscar_cursos_profesor.php",
    method: "POST",
    data: {
      mi_busqueda_curso: mi_busqueda_curso,
      sede: "GENERAL",
      idprofesor: idprofesor,
    },
    success: function (response) {
      $("#tablaCurso tbody").html(response);
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}

function seleccionarCurso(idcurso) {
  $.ajax({
    url: "obtener_curso.php",
    method: "POST",
    data: { idcurso: idcurso },
    success: function (response) {
      const curso = JSON.parse(response);

      // Asignar valores al formulario
      asignarValoresFormulario(curso);

      // Actualizar variables globales y recalcular el total
      actualizarVariablesGlobales(curso);
      const uniformeCheckbox = document.getElementById("uniformeCheckbox");
      actualizarTotal(uniformeCheckbox.checked);

      // Manejar el profesor asignado
      manejarProfesor(curso.idprofesor);

      // Ocultar el modal
      $("#modalCurso").modal("hide");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}

function asignarValoresFormulario(curso) {
  document.getElementById("idCurso").value = curso.idcurso;
  document.getElementById("curso").value = curso.nombre;

  // Duración formateada
  const duracion = curso.duracion;
  const textoDuracion = duracion + " Mes" + (duracion > 1 ? "es" : "");
  document.getElementById("duracion").value = textoDuracion;

  // Precio del curso
  const precio = parseFloat(curso.precio);
  document.getElementById("precio").value = isNaN(precio)
    ? "0.00"
    : formatoMoneda(precio);

  // Fechas
  document.getElementById("fechaComienzo").value = curso.fechaComienzo;
  document.getElementById("fechaTermino").value = curso.fechaTermino;
}

function actualizarVariablesGlobales(curso) {
  precioInscripcion = parseFloat(curso.inscripcion) || 0;
  console.log("Precio de inscripción actualizado:", precioInscripcion);
}

function manejarProfesor(idprofesor) {
  if (idprofesor) {
    buscarProfesor(idprofesor);
  } else {
    console.log("No tiene profesor asignado.");
    document.getElementById("dniP").value = "";
    document.getElementById("nombreP").value = "";
    document.getElementById("apellidoP").value = "";
  }
} 

function seleccionarCursoProfesor(idcurso) {
  $.ajax({
    url: "obtener_curso.php",
    method: "POST",
    data: { idcurso: idcurso },
    success: function (response) {
      var curso = JSON.parse(response);

      var total = curso.precio;
      document.getElementById("importeD").value = total;

      $("#modalCursoProfesor").modal("hide");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}

function buscarProfesor(idprofesor) {
  $.ajax({
    url: "obtener_profesor.php",
    method: "POST",
    data: { idprofesor: idprofesor },
    dataType: "json",
    success: function (profesor) {
      console.log(profesor); // Verifica el contenido de la respuesta

      if (document.getElementById("dniP")) {
        document.getElementById("dniP").value = profesor.dni || "";
      }

      if (document.getElementById("nombreP")) {
        document.getElementById("nombreP").value = profesor.nombre || "";
      }

      if (document.getElementById("apellidoP")) {
        document.getElementById("apellidoP").value = profesor.apellido || "";
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", error);
    },
  });
}
