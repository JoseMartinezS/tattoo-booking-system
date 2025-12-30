<?php
// indexadmin.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <?php
    include '../includes/conexion.php'; // conexión a BD

    // Obtener todas las citas ordenadas por fecha/hora
    $stmt = $pdo->query("SELECT * FROM citas ORDER BY fecha, hora");
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>
        <main>
          <h1>Panel de Administración</h1>
          <table border="1" cellpadding="10" cellspacing="0">
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
                  <td><?= $cita['fecha'] ?></td>
                  <td><?= $cita['hora'] ?></td>
                  <td><?= $cita['estado'] ?></td>
                  <td>
                    <?php if ($cita['estado'] == 'pendiente'): ?>
                      <a href="accion_cita.php?id=<?= $cita['id'] ?>&accion=confirmar">Confirmar</a> |
                      <a href="accion_cita.php?id=<?= $cita['id'] ?>&accion=cancelar">Cancelar</a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </main>


  <?php include '../includes/footer.php'; ?>

</body>
</html>
