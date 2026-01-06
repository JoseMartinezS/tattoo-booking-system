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
        $mail->Body = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
            <meta charset='UTF-8'>
            <style>
                body {
                font-family: Arial, sans-serif;
                background: #f4f6f9;
                margin: 0;
                padding: 0;
                }
                .container {
                max-width: 600px;
                margin: 20px auto;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 6px;
                padding: 20px;
                }
                h2 {
                font-size: 20px;
                color: #007bff;
                margin-bottom: 20px;
                }
                .dato {
                margin-bottom: 12px;
                font-size: 15px;
                color: #333;
                }
                .dato strong {
                display: inline-block;
                width: 120px;
                color: #555;
                }
                .footer {
                margin-top: 30px;
                font-size: 12px;
                color: #999;
                border-top: 1px solid #eee;
                padding-top: 10px;
                }
            </style>
            </head>
            <body>
            <div class='container'>
                <h2>Nueva Cotizacion</h2>
                <div class='dato'><strong>Nombre:</strong> $nombre</div>
                <div class='dato'><strong>Correo:</strong> $email</div>
                <div class='dato'><strong>Descripción:</strong> $descripcion</div>
                <div class='dato'><strong>Tamaño:</strong> $tamano</div>
                <div class='dato'><strong>Ubicación:</strong> $ubicacion</div>
                <div class='footer'>
                Cotizaciones Estudio Miguel · Enviado automáticamente
                </div>
            </div>
            </body>
            </html>
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
