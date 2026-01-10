<?php
session_start();
if (!isset($_SESSION['admin'])) {
    exit("Acceso denegado");
}
include '../includes/conexion.php';

// Generar token único con expiración (sin cita_id)
$token  = bin2hex(random_bytes(16));
$expira = date("Y-m-d H:i:s", strtotime("+1 day"));

$stmt = $pdo->prepare("INSERT INTO tokens (token, expira) VALUES (:token, :expira)");
$stmt->execute([
    ':token'   => $token,
    ':expira'  => $expira
]);

$link = "http://localhost/web/Miguel/agenda/agendar.php?token=$token";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Enlace generado</title>
</head>
<body>
  <h1>Enlace para agendar</h1>
  <p>Comparte este enlace con el cliente:</p>
  <p><a href="<?= $link ?>" target="_blank"><?= $link ?></a></p>
  <a href="indexadmin.php">← Volver al Panel</a>
</body>
</html>
