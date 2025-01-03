<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $name = trim($_POST['con_name'] ?? '');
    $email = trim($_POST['con_email'] ?? '');
    $message = trim($_POST['con_message'] ?? '');

   
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo "Todos los campos son obligatorios.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "El correo electrónico no es válido.";
        exit;
    }

    require 'path/to/PHPMailer/src/PHPMailer.php';
    require 'path/to/PHPMailer/src/SMTP.php';
    require 'path/to/PHPMailer/src/Exception.php';

    
    $mail = new PHPMailer(true);

    try {
       
        $mail->isSMTP();
        $mail->Host = 'smtp.tu-dominio.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'tu-correo@tu-dominio.com'; 
        $mail->Password = 'tu-contraseña'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; 

       
        $mail->setFrom($email, $name);
        $mail->addAddress('destinatario@tu-dominio.com', 'Destinatario'); 
        
        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de contacto de $name";
        $mail->Body = "<h3>Tienes un nuevo mensaje de contacto</h3>
                       <p><strong>Nombre:</strong> $name</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Mensaje:</strong><br>$message</p>";
        $mail->AltBody = "Tienes un nuevo mensaje de contacto\n
                          Nombre: $name\n
                          Email: $email\n
                          Mensaje: $message";

        
        $mail->send();
        http_response_code(200);
        echo "Tu mensaje ha sido enviado con éxito.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Hubo un error al enviar tu mensaje. Error: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
?>
