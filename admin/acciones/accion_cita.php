<?php
include '../../includes/conexion.php';
include '../../includes/conexion_correo.php';

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
            <h2>Tu cita ha sido $nuevoEstado</h2>
            <div class='dato'><strong>Nombre:</strong> {$cita['nombre']}</div>
            <div class='dato'><strong>Fecha:</strong> {$cita['fecha']}</div>
            <div class='dato'><strong>Hora:</strong> {$cita['hora']}</div>
            <div class='dato'><strong>Estado:</strong> $nuevoEstado</div>
            <div class='footer'>
            Estudio Miguel. Confirmacion automatica - Por favor, no responda a este correo.
            </div>
        </div>
        </body>
        </html>
        ";
    $mail->send();

    header("Location: ../indexadmin.php");
    exit;
}
?>
