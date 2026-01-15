<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../includes/conexion.php';

// Insertar nueva disponibilidad evitando duplicados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora  = $_POST['hora'];

    // Validar duplicado
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM disponibilidad WHERE fecha = :fecha AND hora = :hora");
    $stmt->execute([':fecha' => $fecha, ':hora' => $hora]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO disponibilidad (fecha, hora) VALUES (:fecha, :hora)");
        $stmt->execute([':fecha' => $fecha, ':hora' => $hora]);
    }

    header("Location: disponibilidad.php"); exit;
}

// Número de registros por página
$porPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;
$offset = ($paginaActual - 1) * $porPagina;

// Filtros
$filtroFecha = $_GET['filtroFecha'] ?? null;
$filtroEstado = $_GET['estado'] ?? null;

$baseQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM disponibilidad";
$where = [];
$params = [];

if ($filtroFecha) {
    $where[] = "fecha = :fecha";
    $params[':fecha'] = $filtroFecha;
}
if ($filtroEstado === 'disponible') {
    $where[] = "disponible = 1";
}
if ($filtroEstado === 'no-disponible') {
    $where[] = "disponible = 0";
}

if ($where) {
    $baseQuery .= " WHERE " . implode(" AND ", $where);
}

$baseQuery .= " ORDER BY fecha, hora LIMIT :offset, :porPagina";

$stmt = $pdo->prepare($baseQuery);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
$stmt->execute();

$slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de registros
$totalRegistros = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Disponibilidad</title>
  <link rel="stylesheet" href="../../style.css">
  <link rel="stylesheet" href="../../styless/style_disponibilidad.css">
</head>
<body>
  <?php include '../../includes/header.php'; ?>
  
  <main>
    <h1>Gestionar Disponibilidad</h1>
    <a href="../indexadmin.php" class="btn-volver">← Volver al Panel</a>
    
    <!-- Formulario agregar -->
    <form method="POST">
      <label>Fecha:</label>
      <input type="date" name="fecha" required>
      <label>Hora:</label>
      <input type="time" name="hora" required>
      <button type="submit">Agregar horario</button>
    </form>

    <!-- Filtro por fecha -->
    <form method="GET" style="margin-bottom:20px;">
      <label>Filtrar por fecha:</label>
      <input type="date" name="filtroFecha" value="<?= htmlspecialchars($filtroFecha ?? '') ?>">
      <button type="submit">Filtrar</button>
      <a href="disponibilidad.php" style="margin-left:10px;">Quitar filtro</a>
    </form>

    <h2>Horarios disponibles</h2>
    <!-- Tabs de estado -->
    <div class="tabs">
      <a href="disponibilidad.php" 
         class="tab <?= !$filtroEstado ? 'active' : '' ?>">Todos</a>
      <a href="disponibilidad.php?estado=disponible<?= $filtroFecha ? '&filtroFecha='.$filtroFecha : '' ?>" 
         class="tab <?= $filtroEstado=='disponible' ? 'active' : '' ?>">Disponibles</a>
      <a href="disponibilidad.php?estado=no-disponible<?= $filtroFecha ? '&filtroFecha='.$filtroFecha : '' ?>" 
         class="tab <?= $filtroEstado=='no-disponible' ? 'active' : '' ?>">No disponibles</a>
    </div>
    <div class="slots-lista">
      <?php 
      $currentDate = null;
      foreach ($slots as $slot): 
        if ($slot['fecha'] !== $currentDate): 
          $currentDate = $slot['fecha']; ?>
          <h3 class="slot-fecha"><?= date("d/m/Y", strtotime($slot['fecha'])) ?></h3>
        <?php endif; ?>
        
        <div class="slot-card <?= $slot['disponible'] ? 'disponible' : 'no-disponible' ?>">
          <div class="slot-info">
            <span class="slot-hora">🕒 <?= $slot['hora'] ?></span>
            <span class="slot-estado">
              <?= $slot['disponible'] ? '✅ Disponible' : '❌ No disponible' ?>
            </span>
          </div>
          <div class="slot-actions">
            <?php if ($slot['disponible']): ?>
              <a href="../acciones/toggle_disponibilidad.php?id=<?= $slot['id'] ?>" class="btn-cancelar">Marcar como no disponible</a>
            <?php else: ?>
              <a href="../acciones/toggle_disponibilidad.php?id=<?= $slot['id'] ?>" class="btn-confirmar">Marcar como disponible</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Paginación -->
    <div class="paginacion">
      <?php if ($paginaActual > 1): ?>
        <a href="?pagina=<?= $paginaActual - 1 ?><?= $filtroFecha ? '&filtroFecha='.$filtroFecha : '' ?><?= $filtroEstado ? '&estado='.$filtroEstado : '' ?>" class="btn-paginacion">← Anterior</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="?pagina=<?= $i ?><?= $filtroFecha ? '&filtroFecha='.$filtroFecha : '' ?><?= $filtroEstado ? '&estado='.$filtroEstado : '' ?>" 
           class="btn-paginacion <?= $i == $paginaActual ? 'active' : '' ?>">
           <?= $i ?>
        </a>
      <?php endfor; ?>

      <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?= $paginaActual + 1 ?><?= $filtroFecha ? '&filtroFecha='.$filtroFecha : '' ?><?= $filtroEstado ? '&estado='.$filtroEstado : '' ?>" class="btn-paginacion">Siguiente →</a>
      <?php endif; ?>
    </div>
  </main>
  <?php include '../../includes/footer.php'; ?>
</body>
</html>
