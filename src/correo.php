<?php
include "../conexion.php";

$Nombre = $_POST['usuario'];
$dni = $_POST['dni'];
$fecha = $_POST['fechaComienzo'];
$sede = $_POST['sede'];
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
<h3> Comprobante de Inscripcion </h3>

<a href='http://fliasoft.online/src/pdf/reporte.php?dni=$dni&curso=$Mensaje'class='btn btn-success'><i class='fas fa-edit'></i>Ver</a>

  <br><p>No necesitas clave<p>
  <p>Curso: $Mensaje<p>
  <p>Fecha de Inicio: $fecha <p>
  <p hidden>Sede: $sede <p>
  Información para el Alumno: El pago de la inscripción se devuelve únicamente antes del inicio del curso de lo contrario no se devuelve el dinero.
  Muchas gracias por entendernos Eduser.

  \n<br />";
//$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);

$mail->IsSMTP(); 
/** Configurar SMTP **/
$mail->isSMTP();                                      // Indicamos que use SMTP
$mail->Host = 'ssl://smtp.hostinger.com:465';  // Indicamos los servidores SMTP
$mail->SMTPAuth = true;                               // Habilitamos la autenticación SMTP
$mail->Username = 'info@fliasoft.online';                 // SMTP username
$mail->Password = '600269Joni2505';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Habilitar encriptación TLS o SSL
$mail->Port = 465;

if ($mail->Send()){

echo '<script language="javascript">';
echo 'alert("Correo Enviado");';
echo 'window.location="inscripcion.php";';
echo '</script>';
}     
else{
     echo '<script language="javascript">';
	echo 'alert("Error al enviar el correo");';
	echo 'window.location="inscripcion.php";';
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