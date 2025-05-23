<?php
include "../conexion.php";
$idinscripcion = $_POST['idinscripcion'];
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
  //echo "Se envio el Comprobante a: ".$Email;
  if (empty($sede) || empty($dni) || empty($idinscripcion) ) {
    echo '<script language="javascript">';
    echo 'alert("No se pudo enviar el Correo");';
    echo '</script>';
}else{
$Mensaje = $_POST['texcurso1'];  
require 'PHPMailer/class.phpmailer.php';
require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

/** Configurar SMTP **/
$mail->isSMTP();                                      // Indicamos que use SMTP
$mail->Host = 'ssl://smtp.hostinger.com:465';  // Indicamos los servidores SMTP
$mail->SMTPAuth = true;                               // Habilitamos la autenticación SMTP
$mail->Username = 'cobros@sistemaeduser.com';                 // SMTP username
$mail->Password = 'Agostino1831@';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Habilitar encriptación TLS o SSL
$mail->Port = 465;                                 // TCP port

/** Configurar cabeceras del mensaje **/
$mail->From = 'info@fliasoft.online';                       // Correo del remitente
$mail->FromName = $Nombre;           // Nombre del remitente
$mail->Subject = 'Comprobante Eduser';                // Asunto

/** Incluir destinatarios. El nombre es opcional **/
$mail->addAddress($Email);
//$mail->addAddress('destinatario2@correo.com', 'Nombre2');
//$mail->addAddress('destinatario3@correo.com', 'Nombre3');

/** Con RE, CC, BCC **/
//$mail->addReplyTo('info@correo.com', 'Informacion');
//$mail->addCC('cc@correo.com');
//$mail->addBCC('bcc@correo.com');

/** Incluir archivos adjuntos. El nombre es opcional **/
//$mail->addAttachment('/archivos/miproyecto.zip');        
$mail->addAttachment('assets/img/logo.jpeg');

/** Enviarlo en formato HTML **/
$mail->isHTML(true);                                  

/** Configurar cuerpo del mensaje **/
$mail->Body    = "Operador: $Nombre \n<br />". 
"Informacion:
    <h2>Hola $alumno - $apellido </h2>
    <h3> Comprobante de Examen </h3>
    
    <a href='http://sistemaeduser.com/src/pdf/reporteExamen.php?idinscripcion=$idinscripcion&sede=$sede'class='btn btn-success'><i class='fas fa-edit'></i>Ver</a>
    
      <br><p>No necesitas clave<p>
      <p>Curso: $Mensaje<p>
      <p>Fecha de Inicio: $fecha <p>
      <p hidden>Sede: $sede <p>
      
      \n<br />";
//$mail->AltBody = 'Este es el mansaje en texto plano para clientes que no admitan HTML';

/** Para que use el lenguaje español **/
$mail->setLanguage('es');

/** Enviar mensaje... **/
if(!$mail->send()) {
    echo 'El mensaje no pudo ser enviado.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Mensaje y Comprobante enviado correctamente a: '.$Email;
}

}
?>