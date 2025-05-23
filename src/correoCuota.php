<?php
include "../conexion.php";
date_default_timezone_set('America/Argentina/Buenos_Aires');
$feha_actual=date("d-m-Y ");
$total = $_POST['total'];
$Nombre = $_POST['usuario'];
$dni = $_POST['dni'];
$cuota1 = $_POST['cuota1'];
//$fecha = $_POST['fechaComienzo'];
$sede = $_POST['sede'];
$mes = $_POST['mes'];

  $rs = mysqli_query($conexion, "SELECT nombre, apellido, email FROM alumno WHERE dni='$dni'");
  while($row = mysqli_fetch_array($rs))
  {
      $Email=$row['email'];
      $alumno = $row['nombre'];
      $apellido=$row['apellido'];
  }
  echo "Se envio el Comprobante a: ".$Email;
//$Email = $_POST['Email'];
$Mensaje = $_POST['curso'];
//$archivo = $_FILES['adjunto'];

require 'PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer();

$mail->From     = $Email;
$mail->FromName = $Nombre;
$mail->AddAddress($Email);

$mail->WordWrap = 50; 
$mail->IsHTML(true);     
$mail->Subject  =  "Comprobante Eduser";
$mail->Body     =  "Operador: $Nombre \n<br />".       
"Informacion:
<h2>Hola $alumno - $apellido </h2>
<h3> Comprobante de Pago</h3>
<p>No necesitas clave</p>
<a href='http://fliasoft.online/src/pdf/reporteCuotas.php?dni=$dni&curso=$Mensaje&mes=$mes&sede=$sede'class='btn btn-success'><i class='fas fa-edit'></i>Ver Comprobante</a>
  <h4>Curso: $Mensaje</h4>
  <h4>Fecha de Pago: $feha_actual </h4>
  <h4>Concepto: $mes </h4>
  <h4>Pago: $cuota1 </h4>
  <h4>Total Pagado: $total </h4>
  <h4 hidden>Sede: $sede </h4>

  Información para el Alumno: El pago de la inscripción se devuelve únicamente antes del inicio del curso de lo contrario no se devuelve el dinero.
  Muchas gracias por entendernos Eduser.

  \n<br />";
//$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);

$mail->IsSMTP(); 
$mail->Host = "ssl://smtp.gmail.com:465"; //Servidor de Salida.
$mail->SMTPAuth = true; 
$mail->Username = "fliasoft811@gmail.com"; //Correo Electrónico
$mail->Password = "600269joni"; //Contraseña

if ($mail->Send()){

echo '<script language="javascript">';
echo 'alert("Correo Enviado");';
//echo 'window.location="inscripcion.php";';
echo '</script>';
}     
else{
     echo '<script language="javascript">';
	echo 'alert("Error al enviar el correo");';
	//echo 'window.location="inscripcion.php";';
	echo '</script>';
}
?>
<script type="text/javascript">
function correo()
  {
    alert("Se genero el reporte");
    var dni = <?php echo $dni;?>
    var curso = <?php echo $Mensaje;?>
   
    
    var parametros = 
    {
      "buscarG": "1",
      "dni" : dni,
      "curso" : curso
      
    };
    $.ajax(
    {
      data:  parametros,
      error: function()
      {alert("Error");},
    
      success:  function () 
      {
        url = 'pdf/reporteExamen.php?dni=' + dni + '&curso=' + curso;
        window.open(url, '_blank')
      }
    }) 
     
    
  }

  </script>