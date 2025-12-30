<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Header -->
<header>
  <div class="container header-inner" role="navigation" aria-label="Navegación principal">
    <div class="logo">
<img src="/web/Miguel/images/logomiguel.jpg" alt="Logo Miguel">
    </div>
    <nav>
      <ul>
        <li><a href="cotizacion/cotizacion.php">Cotiza tu tatuaje</a></li>
        <li><a href="#sobre-nosotros">Sobre nosotros</a></li>
        <li><a href="#portafolio">Portafolio</a></li>
        <li><a href="#contacto">Contáctanos</a></li>

        <?php if (isset($_SESSION['admin'])): ?>
          <li><a href="/web/Miguel/admin/logout.php">Cerrar sesión </a></li>
        <?php endif; ?>
   
      </ul>
    </nav>
  </div>
</header>
