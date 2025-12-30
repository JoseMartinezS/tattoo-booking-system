<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // si usas Composer para PHPMailer

function crearMailer() {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'josedejesusmartinezsilva@gmail.com';
        $mail->Password   = 'axal spgr dxyb rpdh';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente por defecto
        $mail->setFrom('josedejesusmartinezsilva@gmail.com', 'Estudio Miguel');

        return $mail;
    } catch (Exception $e) {
        die("Error al configurar correo: " . $e->getMessage());
    }
}
?>
