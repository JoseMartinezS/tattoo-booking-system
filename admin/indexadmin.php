<?php
session_start();
require_once __DIR__ . '/../config.php'; // carga BASE_URL y BASE_PATH

if (!isset($_SESSION['admin'])) {
    header("Location: " . BASE_URL . "admin/auth/login.php");
    exit;
}

include '../includes/conexion.php';

include '../includes/admin_consultas_citas.php'; // 👈 aquí se carga toda la lógica
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_indexadmin.css">
  <link rel="stylesheet" href="../styless/style_modal_token.css">

  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <main>
    <h1>Panel de Administración</h1>

    <div class="acciones-panel">
      <a href="views/disponibilidad.php" class="btn-disponibilidad">Gestionar Disponibilidad</a>
      <a href="#" class="btn-compartir" onclick="abrirModalToken()">Generar enlace de agendado</a>
      <a href="views/calendario.php" class="btn-calendario">Ver Calendario</a>
    </div>

    <div class="resumen-tabs">
      <a href="?estado=pendiente&vista=todas" class="card pendiente <?= $estado=='pendiente' ? 'active' : '' ?>">
        🟡 Pendientes: <?= $totalPendientes ?>
      </a>
      <a href="?estado=confirmada&vista=todas" class="card confirmada <?= $estado=='confirmada' ? 'active' : '' ?>">
        ✅ Confirmadas: <?= $totalConfirmadas ?>
      </a>
      <a href="?estado=cancelada&vista=todas" class="card cancelada <?= $estado=='cancelada' ? 'active' : '' ?>">
        ❌ Canceladas: <?= $totalCanceladas ?>
      </a>
      <a href="?vista=todas" class="card todas <?= $estado=='' ? 'active' : '' ?>">
        📋 Todas
      </a>
      </a>
    </div>


    <!-- Filtros por fecha -->
    <div class="filtros-fecha">
      <a href="?filtro=hoy" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='hoy' ? 'active' : '' ?>">Hoy</a>
      <a href="?filtro=semana" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='semana' ? 'active' : '' ?>">Semana</a>
      <a href="?filtro=mes" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='mes' ? 'active' : '' ?>">Mes</a>
      <a href="?filtro=todas" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='todas' ? 'active' : '' ?>">Todas</a>
    </div>

    <!-- Lista de citas en tarjetas -->
<div class="citas-lista">
  <?php foreach ($citas as $cita): ?>
    <div class="cita-card <?= $cita['estado'] ?>">
      <div class="cita-header">
        <span class="cita-nombre"><?= htmlspecialchars($cita['nombre']) ?></span>
        <span class="cita-estado">
          <?= $cita['estado']=='pendiente'?'🟡 Pendiente':($cita['estado']=='confirmada'?'✅ Confirmada':'❌ Cancelada') ?>
        </span>
      </div>
      <div class="cita-info">
        <p><strong>Fecha:</strong> <?= date("d/m/Y", strtotime($cita['fecha'])) ?></p>
        <p><strong>Hora:</strong> <?= date("g:i A", strtotime($cita['hora'])) ?></p>
      </div>
      <div class="cita-actions">
        <button onclick="abrirModal(<?= $cita['id'] ?>)" class="btn-detalle">👁️ Ver detalles</button>
        <?php if ($cita['estado']=='pendiente'): ?>
          <div class="dropdown">
            <button class="dropbtn">⚙️ Acciones</button>
            <div class="dropdown-content">
              <a href="acciones/accion_cita.php?id=<?= $cita['id'] ?>&accion=confirmar">✅ Confirmar</a>
              <a href="acciones/accion_cita.php?id=<?= $cita['id'] ?>&accion=cancelar">❌ Cancelar</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>



    <!-- Paginación -->
    <div class="paginacion">
      <?php if ($paginaActual > 1): ?>
        <a href="?estado=<?= $estado ?>&pagina=<?= $paginaActual - 1 ?>" class="btn-paginacion">← Anterior</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="?estado=<?= $estado ?>&pagina=<?= $i ?>" 
           class="btn-paginacion <?= $i == $paginaActual ? 'active' : '' ?>">
           <?= $i ?>
        </a>
      <?php endfor; ?>

      <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?estado=<?= $estado ?>&pagina=<?= $paginaActual + 1 ?>" class="btn-paginacion">Siguiente →</a>
      <?php endif; ?>
    </div>
  </main>

  <?php include '../includes/footer.php'; ?>

  <!-- Modal Token -->
  <div id="modalToken" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close" onclick="cerrarModalToken()">&times;</span>
      <h2>Enlace generado</h2>
      <div id="modalTokenContent">Cargando...</div>
    </div>
  </div>

  <?php include 'modales/modal_cita.php'; ?>
<script src="../js/modal.js"></script>
<script src="../js/modal_token.js"></script>

</body>
</html>
