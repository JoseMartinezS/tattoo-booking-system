<?php
session_start();
if (!isset($_SESSION['admin'])) { exit("Acceso denegado"); }
include '../includes/conexion.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM citas WHERE id = :id");
$stmt->execute([':id' => $id]);
$cita = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cita) {
    echo "<p>Cita no encontrada.</p>";
    exit;
}
?>

<p><strong>Nombre:</strong> <?= htmlspecialchars($cita['nombre']) ?></p>
<p><strong>Correo:</strong> <?= htmlspecialchars($cita['email']) ?></p>
<p><strong>Teléfono:</strong> <?= htmlspecialchars($cita['telefono']) ?></p>
<p><strong>Fecha:</strong> <?= date("d/m/Y", strtotime($cita['fecha'])) ?></p>
<p><strong>Hora:</strong> <?= date("g:i A", strtotime($cita['hora'])) ?></p>
<p><strong>Estado:</strong> <?= htmlspecialchars($cita['estado']) ?></p>
<p><strong>Nota:</strong> <?= htmlspecialchars($cita['nota'] ?? '') ?></p>
