<?php
// agenda.php
include '../includes/conexion.php';
$stmt = $pdo->query("SELECT * FROM disponibilidad WHERE disponible = 1 ORDER BY fecha, hora");
$slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Validar token 
$token = $_GET['token'] ?? ''; 
$stmt = $pdo->prepare("SELECT * FROM tokens WHERE token = :token AND expira > NOW() AND usado = 0"); 
$stmt->execute([':token' => $token]); 
$row = $stmt->fetch();

if (!$row) 
  { exit("❌ Este enlace ya no es válido."); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Agenda</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_agenda_agenda.css">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <form id="agendaForm" action="guardar_cita.php" method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      
      <h2>Agendar Cita</h2>
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required>

      <label for="email">Correo:</label>
      <input type="email" id="email" name="email" required>

      <label for="telefono">Teléfono:</label>
      <input type="tel" id="telefono" name="telefono" required>


      <label for="slot">Selecciona fecha y hora:</label>
      <select name="slot" id="slot" required>
        <?php foreach ($slots as $slot): ?>
          <option value="<?= $slot['id'] ?>">
            <?= $slot['fecha'] ?> - <?= date("g:i A", strtotime($slot['hora'])) ?>
          </option>
        <?php endforeach; ?>
      </select>


      <button type="submit">Agendar cita</button>
  </form>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
