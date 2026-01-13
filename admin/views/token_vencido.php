<?php
// token_expirado.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enlace vencido</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../styless/style_token_vencido.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main class="token-main">
    <div class="token-container">
      <h1>❌ Enlace vencido</h1>
      <p>Lo sentimos, este enlace ya no es válido. Puede que haya expirado o ya se haya utilizado.</p>
      <a href="../index.php" class="btn-volver">Volver al inicio</a>
    </div>
  </main>

  <?php include '../includes/footer.php'; ?>
</body>

</html>
