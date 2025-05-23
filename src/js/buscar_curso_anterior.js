function buscar_datos_cursos() {
  curso = $("#curso").val();

  var parametros = {
    buscar_curso: "1",
    curso: curso,
  };
  $.ajax({
    data: parametros,
    dataType: "json",
    url: "datosInscripcion.php",
    type: "POST",

    error: function () {
      alert("Error");
    },

    success: function (valoresCu) {
      //$("#duracion").val(valoresCu.duracion);
      //$("#precio").val(valoresCu.precio);
      //$("#total").val(valoresCu.inscripcion);
    },
  });
}
