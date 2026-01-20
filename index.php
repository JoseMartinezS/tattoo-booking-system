<?php 
require_once __DIR__ . '/config.php';
// ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Estudio de Tatuajes — Portafolio</title>
  <link rel="stylesheet" href="style.css">
  <style>
    
  </style>
</head>
<body>

  <?php include BASE_PATH . 'includes/header.php'; ?>

  <!-- Sobre nosotros -->
  <section id="sobre-nosotros">
    <div class="container">
      <h2 class="section-title">Sobre nosotros</h2>
      <div class="about">
        <div>
          <p class="tagline">Más que tatuajes, arte en la piel.</p>
          <p class="muted">
            Somos un estudio dedicado a crear piezas únicas que cuentan historias. Nuestra prioridad es el diseño,
            la higiene y la experiencia del cliente. Trabajamos por cita y asesoramos cada proyecto para que sea
            perfecto para ti.
          </p>
          <p>
            Explora nuestro portafolio y contáctanos para tu próxima pieza.
          </p>
        </div>
        <figure class="figure">
          <!-- Reemplaza esta imagen por una foto del estudio o del tatuador -->
          <img src="images/ESTUDIO DE TATUAJE.jpg" alt="Estudio de tatuajes en acción" />
        </figure>
      </div>
    </div>
  </section>

  <!-- Portafolio -->
  <section id="portafolio">
  <div class="container">
    <h2 class="section-title">Portafolio</h2>
    <div class="portfolio-grid" id="portfolioGrid">

      <!-- Ítem 1: estático -->
      <article class="portfolio-item">
        <img src="images/pruebaa.png" alt="Tatuaje en blanco y negro" />
        <div class="caption">Blackwork — Brazo</div>
      </article>

      <!-- Ítem 2: carrusel -->
      <article class="portfolio-item carousel">
        <div class="carousel-inner">
          <div class="carousel-slide active">
            <img src="images/prueba2.png" alt="Tatuaje geométrico 1" />
          </div>
          <div class="carousel-slide">
            <img src="images/prueba4.png" alt="Tatuaje geométrico 2" />
          </div>
          <div class="carousel-slide">
            <img src="images/pruebaa.png" alt="Tatuaje geométrico 3" />
          </div>
        </div>
        <button class="carousel-btn prev">⟨</button>
        <button class="carousel-btn next">⟩</button>
        <div class="caption">Geométrico — Antebrazo</div>
      </article>

      <!-- Ítem 3: estático -->
      <article class="portfolio-item">
        <img src="images/prueba5.png" alt="Tatuaje tradicional" />
        <div class="caption">Tradicional — Hombro</div>
      </article>

    </div>
  </div>
</section>


<section id="contacto">
  <div class="container">
    <h2 class="section-title" style="color:#fff;">Cotiza tu tatuaje</h2>
    <p>Antes de agendar, solicita tu cotización personalizada.</p>
    <a href="cotizacion.html" class="btn">Ir a cotización</a>
  </div>
</section>

<script>
document.addEventListener("keydown", function(event) {
  if (event.ctrlKey && event.altKey && event.shiftKey && event.key.toLowerCase() === "x") {
    event.preventDefault();
    window.location.href = "admin/auth/login.php";
  }
});
</script>

  <?php include BASE_PATH . 'includes/footer.php'; ?>
</body>
</html>
