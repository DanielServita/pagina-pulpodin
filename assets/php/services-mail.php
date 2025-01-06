<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $name = trim($_POST['con_name'] ?? '');
    $email = trim($_POST['con_email'] ?? '');
    $message = trim($_POST['con_message'] ?? '');

    // Validar campos requeridos
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Validar formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "El correo electrónico no es válido.";
        exit;
    }

    // Incluir librerías de PHPMailer
    require 'path/to/PHPMailer/src/PHPMailer.php';
    require 'path/to/PHPMailer/src/SMTP.php';
    require 'path/to/PHPMailer/src/Exception.php';

    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'mail.pulpodin.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'contacto@pulpodin.com'; // Usuario SMTP
        $mail->Password = 'Bx0#P,,Ds2CB'; // Contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Encriptación
        $mail->Port = 465; // Puerto SMTP

        // Configuración del remitente y destinatario
        $mail->setFrom('contacto@pulpodin.com', 'Formulario de Contacto'); // Correo del remitente
        $mail->addAddress('destinatario@tu-dominio.com', 'Destinatario'); // Cambia al correo del destinatario

        // Contenido del mensaje
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

        // Enviar correo
        $mail->send();
        http_response_code(200);
        echo "Tu mensaje ha sido enviado con éxito.";
    } catch (Exception $e) {
        // Manejo de errores
        http_response_code(500);
        echo "Hubo un error al enviar tu mensaje. Error: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
?>

