<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../includes/conexion.php';

// Insertar nueva disponibilidad
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora  = $_POST['hora'];
    $stmt = $pdo->prepare("INSERT INTO disponibilidad (fecha, hora) VALUES (:fecha, :hora)");
    $stmt->execute([':fecha' => $fecha, ':hora' => $hora]);

    // Redirigir para limpiar el formulario y evitar duplicados al recargar 
    header("Location: disponibilidad.php"); exit;
}

// Obtener todas las disponibilidades
$stmt = $pdo->query("SELECT * FROM disponibilidad ORDER BY fecha, hora");
$slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Disponibilidad</title>
  <link rel="stylesheet" href="../style.css">
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

    <h2>Horarios disponibles</h2>
    <table border="1" cellpadding="10">
      <tr><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acciones</th></tr>
      <?php foreach ($slots as $slot): ?>
        <tr>
          <td><?= $slot['fecha'] ?></td>
          <td><?= $slot['hora'] ?></td>
          <td><?= $slot['disponible'] ? 'Disponible' : 'No disponible' ?></td>
          <td>
            <a href="toggle_disponibilidad.php?id=<?= $slot['id'] ?>">Cambiar estado</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
  <?php include '../includes/footer.php'; ?>
</body>
</html>
