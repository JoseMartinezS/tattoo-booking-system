<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include '../includes/conexion.php';

// Contadores de estados
$totalPendientes = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'pendiente'")->fetchColumn();
$totalConfirmadas = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'confirmada'")->fetchColumn();
$totalCanceladas = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'cancelada'")->fetchColumn();

// Filtro por estado (según tab de lista)
$estado = $_GET['estado'] ?? '';
if ($estado) {
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE estado = :estado ORDER BY fecha, hora");
    $stmt->execute([':estado' => $estado]);
} else {
    $stmt = $pdo->query("SELECT * FROM citas ORDER BY fecha, hora");
}
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vista seleccionada (tabla o calendario)
$vista = $_GET['vista'] ?? 'todas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_indexadmin.css">

  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <main>
    <h1>Panel de Administración</h1>

    <!-- Resumen -->
    <div class="resumen">
      <div class="card pendiente">Pendientes: <?= $totalPendientes ?></div>
      <div class="card confirmada">Confirmadas: <?= $totalConfirmadas ?></div>
      <div class="card cancelada">Canceladas: <?= $totalCanceladas ?></div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <a href="?estado=pendiente&vista=todas" class="tab <?= $estado=='pendiente' && $vista!='calendario' ? 'active' : '' ?>">Pendientes</a>
      <a href="?estado=confirmada&vista=todas" class="tab <?= $estado=='confirmada' && $vista!='calendario' ? 'active' : '' ?>">Confirmadas</a>
      <a href="?estado=cancelada&vista=todas" class="tab <?= $estado=='cancelada' && $vista!='calendario' ? 'active' : '' ?>">Canceladas</a>
      <a href="?vista=todas" class="tab <?= $estado=='' && $vista!='calendario' ? 'active' : '' ?>">Todas</a>
      <a href="?vista=calendario" class="tab <?= $vista=='calendario' ? 'active' : '' ?>">Calendario</a>
    </div>

    <?php if ($vista === 'calendario'): ?>
      <!-- Contenedor del calendario -->
      <div id="calendar"></div>

      <!-- Pasar eventos al JS -->
      <script>
        window.citasEventos = [
          <?php foreach ($citas as $cita): ?>
            {
              title: "<?= htmlspecialchars($cita['nombre']) ?> (<?= $cita['estado'] ?>)",
              start: "<?= date('Y-m-d\TH:i:s', strtotime($cita['fecha'].' '.$cita['hora'])) ?>",
              color: "<?= $cita['estado']=='pendiente' ? '#ffc107' : ($cita['estado']=='confirmada' ? '#28a745' : '#dc3545') ?>"
            },
          <?php endforeach; ?>
        ];
      </script>
    <?php else: ?>
      <!-- Tabla -->
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($citas as $cita): ?>
            <tr>
              <td><?= htmlspecialchars($cita['nombre']) ?></td>
              <td><?= htmlspecialchars($cita['email']) ?></td>
              <td><?= date("d/m/Y", strtotime($cita['fecha'])) ?></td>
              <td><?= date("g:i A", strtotime($cita['hora'])) ?></td>
              <td><?= $cita['estado'] ?></td>
              <td>
                <?php if ($cita['estado'] == 'pendiente'): ?>
                  <a href="accion_cita.php?id=<?= $cita['id'] ?>&accion=confirmar" class="btn-accion btn-confirmar">Confirmar</a>
                  <a href="accion_cita.php?id=<?= $cita['id'] ?>&accion=cancelar" class="btn-accion btn-cancelar">Cancelar</a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>

  <?php include '../includes/footer.php'; ?>

  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <!-- Tu script (después de definir window.citasEventos) -->
  <script src="../js/script.js?v=1.0"></script>
</body>
</html>
