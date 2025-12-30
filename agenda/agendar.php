<?php
// agenda.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Agenda</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <body>
   <form action="guardar_cita.php" method="POST">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required>

      <label for="email">Correo:</label>
      <input type="email" id="email" name="email" required>

      <label for="fecha">Fecha:</label>
      <input type="date" id="fecha" name="fecha" required>

      <label for="hora">Hora:</label>
      <input type="time" id="hora" name="hora" required>

      <button type="submit">Agendar cita</button>
    </form>

  </body>

  <?php include '../includes/footer.php'; ?>

</body>
</html>
