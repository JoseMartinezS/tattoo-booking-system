<?php
include '../includes/conexion.php';
include '../includes/conexion_correo.php';

$id = $_GET['id'] ?? null;
$accion = $_GET['accion'] ?? null;

if ($id && $accion) {
    $nuevoEstado = ($accion == 'confirmar') ? 'confirmada' : 'cancelada';

    // Actualizar estado en BD
    $stmt = $pdo->prepare("UPDATE citas SET estado = :estado WHERE id = :id");
    $stmt->execute([':estado' => $nuevoEstado, ':id' => $id]);

    // Obtener datos de la cita
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    // Enviar correo al cliente
    $mail = crearMailer();
    $mail->addAddress($cita['email'], $cita['nombre']);
    $mail->isHTML(true);
    $mail->Subject = "Tu cita ha sido $nuevoEstado";
    $mail->Body    = "Hola {$cita['nombre']},<br>Tu cita para el día <b>{$cita['fecha']}</b> a las <b>{$cita['hora']}</b> ha sido <b>$nuevoEstado</b>.";
    $mail->send();

    header("Location: indexadmin.php");
    exit;
}
?>
