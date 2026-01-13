<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
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
    </div>

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
      <a href="views/calendario.php" class="tab">Calendario</a>    
    </div>

    <!-- Filtros por fecha -->
    <div class="filtros-fecha">
      <a href="?filtro=hoy" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='hoy' ? 'active' : '' ?>">Hoy</a>
      <a href="?filtro=semana" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='semana' ? 'active' : '' ?>">Semana</a>
      <a href="?filtro=mes" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='mes' ? 'active' : '' ?>">Mes</a>
      <a href="?filtro=todas" class="btn-filtro <?= ($_GET['filtro'] ?? '')=='todas' ? 'active' : '' ?>">Todas</a>
    </div>

    <!-- Tabla -->
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Teléfono</th>
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
            <td><?= htmlspecialchars($cita['telefono']) ?></td>
            <td><?= date("d/m/Y", strtotime($cita['fecha'])) ?></td>
            <td><?= date("g:i A", strtotime($cita['hora'])) ?></td>
            <td><?= $cita['estado'] ?></td>
            <td>
              <?php if ($cita['estado'] == 'pendiente'): ?>
                  <a href="acciones/accion_cita.php?id=<?= $cita['id'] ?>&accion=confirmar" class="btn-accion btn-confirmar">Confirmar</a>
                  <a href="acciones/accion_cita.php?id=<?= $cita['id'] ?>&accion=cancelar" class="btn-accion btn-cancelar">Cancelar</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

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

  <script src="../js/modal_token.js"></script>
</body>
</html>
