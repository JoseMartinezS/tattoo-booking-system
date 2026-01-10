<?php
// guardar_cita.php
include '../includes/conexion.php';
include '../includes/conexion_correo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? null;
    $email  = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $slotId = $_POST['slot'] ?? null;
    $token = $_POST['token'] ?? null;

    $descripcion = null;

    try {
        //Validar token
        $stmt = $pdo->prepare("SELECT * FROM tokens WHERE token = :token AND expira > NOW() AND usado = 0");
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();

        if (!$row) {
            die("❌ Este enlace ya no es válido.");
        }

        // Obtener fecha y hora del slot seleccionado
        $stmt = $pdo->prepare("SELECT fecha, hora FROM disponibilidad WHERE id = :id AND disponible = 1");
        $stmt->execute([':id' => $slotId]);
        $slot = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$slot) {
            die("❌ El horario seleccionado ya no está disponible.");
        }

        $fecha = $slot['fecha'];
        $hora  = $slot['hora'];

        // Insertar cita
        $stmt = $pdo->prepare("INSERT INTO citas (nombre, email, telefono, fecha, hora, descripcion) 
                       VALUES (:nombre, :email, :telefono, :fecha, :hora, :descripcion)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':telefono' => $telefono,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':descripcion' => $descripcion
        ]);


        // Marcar el slot como ocupado
        $stmt = $pdo->prepare("UPDATE disponibilidad SET disponible = 0 WHERE id = :id");
        $stmt->execute([':id' => $slotId]);

        // Enviar correos
        $mail = crearMailer();
        $mail->charset = 'UTF-8';
        $mail->Encoding = 'base64';

        // Cliente
        $mail->addAddress($email, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmacion de cita - Estudio Miguel';
        $mail->Body = "
        <html>
        <head>
            <style>
            body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; }
            .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            h2 { color: #007bff; }
            p { color: #333; }
            .footer { margin-top: 20px; font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class='card'>
            <h2>Hola $nombre,</h2>
            <p>Tu cita fue registrada para el dia <b>".date("d/m/Y", strtotime($fecha))."</b> a las <b>".date("g:i A", strtotime($hora))."</b>.</p>
            <p>Miguel la confirmara pronto.</p>
            <div class='footer'>
                <p>Estudio Miguel © ".date("Y")."</p>
            </div>
            </div>
        </body>
        </html>
        ";
        $mail->send();


        // Miguel
        $mail->clearAddresses();
        $mail->addAddress('josedejesusmartinezsilva@gmail.com', 'Miguel');
        $mail->Subject = 'Nueva cita registrada';
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h3>Nueva cita registrada</h3>
            <table border='1' cellpadding='8' cellspacing='0'>
            <tr><td><b>Nombre</b></td><td>$nombre</td></tr>
            <tr><td><b>Correo</b></td><td>$email</td></tr>
            <tr><td><b>Teléfono</b></td><td>$telefono</td></tr>
            <tr><td><b>Fecha</b></td><td>".date("d/m/Y", strtotime($fecha))."</td></tr>
            <tr><td><b>Hora</b></td><td>".date("g:i A", strtotime($hora))."</td></tr>
            </table>
        </body>
        </html>
        ";
        $mail->send();


        header("Location: cita_registrada.php");
        exit();

    } catch (PDOException $e) {
        echo "❌ Error al registrar la cita: " . $e->getMessage();
    }
} else {
    echo "Acceso inválido.";
}
