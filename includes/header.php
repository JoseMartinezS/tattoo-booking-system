<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php'; // ajusta según la ubicación del header.php
?>

<!-- Header -->
<header>
  <div class="container header-inner" role="navigation" aria-label="Navegación principal">
    <div class="logo">
        <a href="<?= BASE_URL ?>index.php">
          <img src="<?= BASE_URL ?>images/logomiguel.jpg" alt="Logo Miguel">
        </a>
    </div>

    <nav>
      <ul>
        <li><a href="<?= BASE_URL ?>cotizacion/cotizacion.php">Cotiza tu tatuaje</a></li>
        <li><a href="https://www.facebook.com/tupagina" target="_blank">Facebook</a></li>
        <li><a href="https://www.instagram.com/tupagina" target="_blank">Instagram</a></li>
        <li><a href="https://wa.me/528661365505" target="_blank">WhatsApp</a></li>

        <?php if (isset($_SESSION['admin'])): ?>
          <li><a href="<?= BASE_URL ?>admin/auth/logout.php">Cerrar sesión</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
