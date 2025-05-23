<?php

// Cargar las clases necesarias de PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer;
        $this->setupSMTP();
    }

    private function setupSMTP() {
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'acamediayolanda@gmail.com';
        $this->mailer->Password = 'iychpovwmgvcfngm';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        $this->mailer->setLanguage('es');
    }

    public function enviarCorreo($to, $subject, $body, $attachments = []) {
        $this->mailer->clearAddresses();
        $this->mailer->clearAttachments();
        
        $this->mailer->addAddress($to);
        $this->mailer->Subject = $subject;
        $this->mailer->isHTML(true);
        $this->mailer->Body = $body;

        foreach ($attachments as $attachment) {
            $this->mailer->addAttachment($attachment);
        }

        if (!$this->mailer->send()) {
            throw new Exception("Error al enviar el correo: " . $this->mailer->ErrorInfo);
        }
    }
}
