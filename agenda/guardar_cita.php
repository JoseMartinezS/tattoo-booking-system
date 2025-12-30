<?php
// guardar_cita.php
include '../includes/conexion.php'; // Incluimos la conexión
include '../includes/conexion_correo.php'; // configuración de correo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'] ?? null;
    $email = $_POST['email'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $hora = $_POST['hora'] ?? null;

    // Como decidimos no usar descripcion en el formulario, la mandamos como NULL
    $descripcion = null;

    try {
        // Preparar la consulta con parámetros seguros
        $stmt = $pdo->prepare("INSERT INTO citas (nombre, email, fecha, hora, descripcion) 
                               VALUES (:nombre, :email, :fecha, :hora, :descripcion)");

        // Ejecutar con los valores
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':descripcion' => $descripcion
        ]);

        header("Location: cita_registrada.php");
        exit();
        // ... después del INSERT exitoso

        $mail = crearMailer();

        // Correo al cliente
        $mail->addAddress($email, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de cita';
        $mail->Body    = "Hola $nombre,<br>Tu cita fue registrada para el día <b>$fecha</b> a las <b>$hora</b>.<br>Miguel la confirmará pronto.";
        $mail->send();

        // Correo a Miguel
        $mail->clearAddresses();
        $mail->addAddress('miguel@estudio-miguel.com', 'Miguel');
        $mail->Subject = 'Nueva cita registrada';
        $mail->Body    = "Nueva cita registrada:<br>Nombre: $nombre<br>Correo: $email<br>Fecha: $fecha<br>Hora: $hora";
        $mail->send();


    } catch (PDOException $e) {
        echo "❌ Error al registrar la cita: " . $e->getMessage();
    }
} else {
    echo "Acceso inválido.";
}
?>
