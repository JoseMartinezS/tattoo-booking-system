<?php
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre     = htmlspecialchars($_POST['nombre'] ?? '');
    $email      = htmlspecialchars($_POST['email'] ?? '');
    $descripcion= htmlspecialchars($_POST['descripcion'] ?? '');
    $tamano     = htmlspecialchars($_POST['tamano'] ?? '');
    $ubicacion  = htmlspecialchars($_POST['ubicacion'] ?? '');
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Validar reCAPTCHA v3
    $secretKey = "6Le2iDcsAAAAALg2rWnUy9GRL8BNyTtci1uC85zt";
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $responseCaptcha = file_get_contents($verifyUrl . "?secret=" . $secretKey . "&response=" . $recaptchaResponse);
    $responseKeys = json_decode($responseCaptcha, true);

    if (!$responseKeys["success"] || $responseKeys["score"] < 0.5) {
        die("Error: Captcha inválido o sospechoso.");
    }

    // Si el captcha es válido, enviar correo
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'josedejesusmartinezsilva@gmail.com';
        $mail->Password   = 'axal spgr dxyb rpdh';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('josedejesusmartinezsilva@gmail.com', 'Cotizaciones Estudio Miguel');
        $mail->addAddress('josedejesusmartinezsilva@gmail.com', 'Jose');

        $mail->isHTML(true);
        $mail->Subject = 'Nueva cotización';
        $mail->Body    = "
            <h3>Detalles del cliente</h3>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Correo:</strong> $email</p>
            <p><strong>Descripción:</strong> $descripcion</p>
            <p><strong>Tamaño:</strong> $tamano</p>
            <p><strong>Ubicación:</strong> $ubicacion</p>
        ";
        if(!empty($_FILES['imagen']['tmp_name'])) {
            $mail->addAttachment($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
        }

        

        $mail->send();
        header("Location: cotizacion_enviada.php");
        exit();
        
    } catch (Exception $e) {
        echo "Error al enviar: {$mail->ErrorInfo}";

        header("Location: cotizacion_error.php");
        exit;

    }
}
