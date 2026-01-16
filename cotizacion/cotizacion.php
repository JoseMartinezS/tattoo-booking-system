<?php
require_once __DIR__ . '/../config.php'; // subimos un nivel para incluir config.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cotización</title>

  <!-- CSS con BASE_URL -->
  <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>styless/stylecotizacion.css">
</head>
<body>

  <!-- Header -->
  <?php include BASE_PATH . 'includes/header.php'; ?>

  <div class="cotizacion-container">
    <div class="form-section">
      <form id="cotizacionForm" action="<?= BASE_URL ?>cotizacion/enviar_cotizacion.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Correo:</label>
        <input type="email" id="email" name="email" required>

        <label for="descripcion">Descripción del tatuaje:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="tamano">Tamaño aproximado:</label>
        <input type="text" id="tamano" name="tamano">

        <label for="ubicacion">Ubicación en el cuerpo:</label>
        <input type="text" id="ubicacion" name="ubicacion">

        <label for="imagen">Adjuntar imagen (opcional):</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <button type="submit">Enviar cotización</button>
      </form>
    </div>

    <div class="image-section">
      <img src="<?= BASE_URL ?>images/Cotizacion.jpg" alt="Diseño de tatuaje">
    </div>
  </div>

  <!-- Script de reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=6Le2iDcsAAAAAKf2cmkbYsBwd9_vXNypS1kDGovQ"></script>
  <script>
    document.getElementById('cotizacionForm').addEventListener('submit', function(event) {
      event.preventDefault(); // detener envío hasta tener token
      grecaptcha.ready(function() {
        grecaptcha.execute('6Le2iDcsAAAAAKf2cmkbYsBwd9_vXNypS1kDGovQ', {action: 'submit'}).then(function(token) {
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'g-recaptcha-response';
          input.value = token;
          document.getElementById('cotizacionForm').appendChild(input);
          document.getElementById('cotizacionForm').submit();
        });
      });
    });
  </script>

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <!-- Footer -->
  <?php include BASE_PATH . 'includes/footer.php'; ?>

</body>
</html>
