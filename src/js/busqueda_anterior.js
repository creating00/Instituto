function ShowSelected() {
  /* Para obtener el valor 
    var cod = document.getElementById("sedes").value;
    alert(cod);*/

  /* Para obtener el texto */
  var combo = document.getElementById("sedes");
  var selected = combo.options[combo.selectedIndex].text;
  $("#sede").val(selected);
  //alert(selected);
}
//$('#tbl').click(function () {
//alert('hiciste click en el input');
//});

//$('#tbl tr').on('click', function(){
//var dato = $(this).find('td:first').html();
//$('#curso').val(dato);
//});

function mi_busqueda() {
  buscar = document.getElementById("cuadro_buscar").value;
  var parametros = {
    usuario: $("#usuario").val(),
    sede: $("#sede").val(),
    mi_busqueda: buscar,
    accion: "4",
  };

  $.ajax({
    data: parametros,
    url: "tablas.php",
    type: "POST",

    beforesend: function () {
      $("#mostrar_mensaje").html("Mensaje antes de Enviar");
    },

    success: function (mensaje) {
      $("#mostrar_mensaje").html(mensaje);
    },
  });
}

function mi_busqueda_curso() {
  buscar = document.getElementById("cuadro_buscar_curso").value;
  var parametros = {
    mi_busqueda_curso: buscar,
    sede: $("#sede").val(),
    accion: "4",
  };

  $.ajax({
    data: parametros,
    url: "tablas.php",
    type: "POST",

    beforesend: function () {
      $("#mostrar_curso").html("Mensaje antes de Enviar");
    },

    success: function (mensaje) {
      $("#mostrar_curso").html(mensaje);
    },
  });
}

function mi_busquedaP() {
  buscar = document.getElementById("cuadro_buscarP").value;
  var parametros = {
    usuario: $("#usuario").val(),
    sede: $("#sede").val(),
    mi_busquedaP: buscar,
    accion: "4",
  };

  $.ajax({
    data: parametros,
    url: "tablas.php",
    type: "POST",

    beforesend: function () {
      $("#mostrar_mensajeP").html("Mensaje antes de Enviar");
    },

    success: function (mensaje) {
      $("#mostrar_mensajeP").html(mensaje);
    },
  });
}

function buscar_datos_alumnos(event) {
  // Verifica si la tecla presionada es Enter (código 13)
  if (event.key !== "Enter") return;

  let dni = $("#dni").val();
  let sede = $("#sede").val();

  var parametros = {
    buscar: "1",
    dni: dni,
    sede: sede,
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
      $("#nombre").val(valores.nombre);
      $("#apellido").val(valores.apellido);
      $("#alumno_id").val(valores.idalumno);

      verificarCuotas();

      if (typeof cargarTablaCursos === "function") {
        cargarTablaCursos(valores.idalumno);
      } else {
        console.log("El método cargarTablaCursos no está definido.");
      }
    },
  });
}
