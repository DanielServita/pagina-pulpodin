<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $name = trim($_POST['con_name'] ?? '');
    $email = trim($_POST['con_email'] ?? '');
    $phone = trim($_POST['con_phone'] ?? '');
    $budget = trim($_POST['con_budget'] ?? '');
    $message = trim($_POST['con_message'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($budget) || empty($message)) {
        http_response_code(400);
        echo "Todos los campos son obligatorios.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "El correo electrónico no es válido.";
        exit;
    }

    // Configuración del correo
    $to = 'contacto@pulpodin.com'; // Correo del destinatario
    $subject = "Nuevo mensaje de contacto de $name";
    $headers = "From: contacto@pulpodin.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $body = "<h3>Tienes un nuevo mensaje de contacto</h3>
             <p><strong>Nombre:</strong> $name</p>
             <p><strong>Email:</strong> $email</p>
             <p><strong>Teléfono:</strong> $phone</p>
             <p><strong>Presupuesto del proyecto:</strong> $budget</p>
             <p><strong>Mensaje:</strong><br>$message</p>";

    // Parámetros SMTP
    $smtp_server = 'mail.pulpodin.com'; // Servidor SMTP
    $smtp_port = 465; // Puerto SMTP
    $smtp_username = 'contacto@pulpodin.com'; // Usuario SMTP
    $smtp_password = 'Bx0#P,,Ds2CB'; // Contraseña SMTP

    // Crear el correo usando sockets
    $sock = fsockopen('ssl://' . $smtp_server, $smtp_port, $errno, $errstr, 10);
    if (!$sock) {
        http_response_code(500);
        echo "No se pudo conectar al servidor SMTP: $errstr ($errno)";
        exit;
    }

    $response = fgets($sock);
    fputs($sock, "EHLO " . $_SERVER['SERVER_NAME'] . "\r\n");
    $response = fgets($sock);
    fputs($sock, "AUTH LOGIN\r\n");
    $response = fgets($sock);
    fputs($sock, base64_encode($smtp_username) . "\r\n");
    $response = fgets($sock);
    fputs($sock, base64_encode($smtp_password) . "\r\n");
    $response = fgets($sock);
    fputs($sock, "MAIL FROM: <$smtp_username>\r\n");
    $response = fgets($sock);
    fputs($sock, "RCPT TO: <$to>\r\n");
    $response = fgets($sock);
    fputs($sock, "DATA\r\n");
    $response = fgets($sock);
    fputs($sock, "Subject: $subject\r\n$headers\r\n$body\r\n.\r\n");
    $response = fgets($sock);
    fputs($sock, "QUIT\r\n");
    $response = fgets($sock);

    fclose($sock);

    http_response_code(200);
    echo "Tu mensaje ha sido enviado con éxito.";
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
?>
