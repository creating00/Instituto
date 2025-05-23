<?php
$Nombre = $_POST['Nombre'];
$Email = $_POST['Email'];
$Mensaje = $_POST['Mensaje'];
$archivo = $_FILES['adjunto'];

require 'PHPMailer/class.phpmailer.php';
require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer();

$mail->From     = $Email;
$mail->FromName = $Nombre;
$mail->AddAddress($Email);

$mail->WordWrap = 50; 
$mail->IsHTML(true);     
$mail->Subject  =  "Contacto";
$mail->Body     =  "Operador: $Nombre \n<br />".       
"Mensaje: $Mensaje \n<br />";
$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);

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
echo 'window.location="index.php";';
echo '</script>';
}     
else{
     echo '<script language="javascript">';
	echo 'alert("Error al enviar el correo");';
	echo 'window.location="index.php";';
	echo '</script>';
}
?>