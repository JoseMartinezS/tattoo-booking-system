<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Header -->
<header>
  <div class="container header-inner" role="navigation" aria-label="Navegación principal">
    <div class="logo">
        <a href="/web/Miguel/index.php">
          <img src="/web/Miguel/images/logomiguel.jpg" alt="Logo Miguel">
        </a>
      </div>

    <nav>
      <ul>
        <!-- Mantener la cotización -->
        <li><a href="cotizacion/cotizacion.php">Cotiza tu tatuaje</a></li>

        <!-- Redes sociales -->
        <li><a href="https://www.facebook.com/tupagina" target="_blank">Facebook</a></li>
        <li><a href="https://www.instagram.com/tupagina" target="_blank">Instagram</a></li>
        <li><a href="https://wa.me/528661365505" target="_blank">WhatsApp</a></li>

        <!-- Botón de cerrar sesión solo si está logueado -->
        <?php if (isset($_SESSION['admin'])): ?>
          <li><a href="/web/Miguel/admin/auth/logout.php">Cerrar sesión</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
