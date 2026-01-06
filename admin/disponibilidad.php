<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../includes/conexion.php';

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

// Filtro por fecha (opcional)
$filtroFecha = $_GET['filtroFecha'] ?? null;
if ($filtroFecha) {
    $stmt = $pdo->prepare("SELECT * FROM disponibilidad WHERE fecha = :fecha ORDER BY fecha, hora");
    $stmt->execute([':fecha' => $filtroFecha]);
} else {
    $stmt = $pdo->query("SELECT * FROM disponibilidad ORDER BY fecha, hora");
}
$slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Disponibilidad</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_disponibilidad.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>
  
  <main>
    <h1>Gestionar Disponibilidad</h1>
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
    <table>
      <tr><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acciones</th></tr>
      <?php 
      $currentDate = null;
      foreach ($slots as $slot): 
        if ($slot['fecha'] !== $currentDate): 
          $currentDate = $slot['fecha']; ?>
          <tr>
            <td colspan="4" style="background:#e9ecef; font-weight:bold; text-align:left;">
              <?= $slot['fecha'] ?>
            </td>
          </tr>
        <?php endif; ?>
        <tr>
          <td></td>
          <td><?= $slot['hora'] ?></td>
          <td>
            <?php if ($slot['disponible']): ?>
              <span style="color:green;">✅ Disponible</span>
            <?php else: ?>
              <span style="color:red;">❌ No disponible</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($slot['disponible']): ?>
              <a href="toggle_disponibilidad.php?id=<?= $slot['id'] ?>" class="btn-cancelar">Marcar como no disponible</a>
            <?php else: ?>
              <a href="toggle_disponibilidad.php?id=<?= $slot['id'] ?>" class="btn-confirmar">Marcar como disponible</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
  <?php include '../includes/footer.php'; ?>
</body>
</html>
