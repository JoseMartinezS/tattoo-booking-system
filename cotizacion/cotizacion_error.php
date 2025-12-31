<?php 
// cotizacion_error.php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Error en Cotización</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_cotizacion_enviada.css">
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <div class="mensaje-error">
    <h2>❌ Ocurrió un problema al enviar tu cotización</h2>
    <p>Por favor intenta nuevamente más tarde o contáctanos directamente por WhatsApp/Instagram.</p>
    <a href="cotizacion.php">Volver al formulario</a>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
