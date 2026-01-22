<?php
session_start();
require_once __DIR__ . '/../../config.php'; // carga BASE_URL y BASE_PATH

if (!isset($_SESSION['admin'])) {
    header("Location: " . BASE_URL . "admin/auth/login.php");
    exit;
}

include '../../includes/conexion.php';

// Cargar citas
$stmt = $pdo->query("SELECT * FROM citas ORDER BY fecha, hora");
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calendario de citas</title>
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <!-- CSS general -->
  <link rel="stylesheet" href="../../style.css">
  <!-- CSS específico del calendario -->
  <link rel="stylesheet" href="../../styless/style_modal.css">
  <link rel="stylesheet" href="../../styless/style_calendario.css">
</head>
<body>
  <?php include '../../includes/header.php'; ?>

  <main>
    <h1>Calendario de citas</h1>
    <!-- Botón para volver -->
    <a href="../indexadmin.php" class="volver-panel">← Volver al Panel de Administración</a>

    <div id="calendar"></div>

  </main>
  <?php include '../modales/modal_cita.php'; ?>


  <?php include '../../includes/footer.php'; ?>

  <script>
    window.citasEventos = [
      <?php foreach ($citas as $cita): ?>
        {
          id: "<?= $cita['id'] ?>",
          title: "<?= htmlspecialchars($cita['nombre']) ?> (<?= $cita['estado'] ?>)",
          start: "<?= date('Y-m-d\TH:i:s', strtotime($cita['fecha'].' '.$cita['hora'])) ?>",
          color: "<?= $cita['estado']=='pendiente' ? '#ffc107' : ($cita['estado']=='confirmada' ? '#28a745' : '#dc3545') ?>"
        },
      <?php endforeach; ?>
    ];
  </script>

  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="../../js/script.js?v=1.0"></script>
  <script src="../../js/modal.js"></script>

</body>
</html>
