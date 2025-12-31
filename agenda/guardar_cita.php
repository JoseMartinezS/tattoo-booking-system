<?php
// guardar_cita.php
include '../includes/conexion.php';
include '../includes/conexion_correo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? null;
    $email  = $_POST['email'] ?? null;
    $slotId = $_POST['slot'] ?? null;

    $descripcion = null;

    try {
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
        $stmt = $pdo->prepare("INSERT INTO citas (nombre, email, fecha, hora, descripcion) 
                               VALUES (:nombre, :email, :fecha, :hora, :descripcion)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':descripcion' => $descripcion
        ]);

        // Marcar el slot como ocupado
        $stmt = $pdo->prepare("UPDATE disponibilidad SET disponible = 0 WHERE id = :id");
        $stmt->execute([':id' => $slotId]);

        // Enviar correos
        $mail = crearMailer();

        // Cliente
        $mail->addAddress($email, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de cita';
        $mail->Body    = "Hola $nombre,<br>Tu cita fue registrada para el día <b>$fecha</b> a las <b>$hora</b>.<br>Miguel la confirmará pronto.";
        $mail->send();

        // Miguel
        $mail->clearAddresses();
        $mail->addAddress('miguel@estudio-miguel.com', 'Miguel');
        $mail->Subject = 'Nueva cita registrada';
        $mail->Body    = "Nueva cita registrada:<br>Nombre: $nombre<br>Correo: $email<br>Fecha: $fecha<br>Hora: $hora";
        $mail->send();

        header("Location: cita_registrada.php");
        exit();

    } catch (PDOException $e) {
        echo "❌ Error al registrar la cita: " . $e->getMessage();
    }
} else {
    echo "Acceso inválido.";
}
