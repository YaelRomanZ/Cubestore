
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Acerca de | CubeStore</title>
  <link rel="icon" href="img/logo.png" type="image/png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilo.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" type="module"></script> 
  <style>
    .hero-about {
      background: url('img/acerca.jpg') center/cover no-repeat;
      min-height: 70vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      position: relative;
    }
    .hero-about::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }
    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
    }
    .hero-content h1 {
      font-size: 3rem;
      font-weight: 700;
      background: linear-gradient(90deg, #00bfff, #ff69b4);
      -webkit-background-clip: text;
      color: transparent;
    }

      .contador-carrito {
  position: absolute;
  background: #ff4747;
  color: white;
  font-size: 0.75rem;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  top: 5px;
  right: -5px;
  font-weight: bold;
  transition: transform 0.2s ease;
}

.contador-carrito.animate {
  transform: scale(1.3);
}


    /* Animación suave de fade-in + slide-up */
@keyframes fadeUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Secciones que entran con animación */
.hero-about,
.py-5,
#equipo-section {
  opacity: 0;
  animation: fadeUp 0.8s forwards;
}

  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-white py-0 fixed-top">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span><i id="barras" class="fa-solid fa-bars"></i></span>
      </button>

      <a class="navbar-brand mx-auto" href="index.php">
        <img src="img/logo.png" alt="CubeStore Logo" height="40" width="40">
      </a>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto d-flex align-items-center justify-content-around w-100">
          <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="catalogoDropdown" role="button" data-bs-toggle="dropdown">Catálogo</a>
            <ul class="dropdown-menu" aria-labelledby="catalogoDropdown">
              <li><a class="dropdown-item" href="catalogo.php?categoria=bebes">Bebés</a></li>
              <li><a class="dropdown-item" href="catalogo.php?categoria=infantil">Infantil</a></li>
              <li><a class="dropdown-item" href="catalogo.php?categoria=adulto">Adultos</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="acercaDropdown" role="button" data-bs-toggle="dropdown">Acerca de</a>
            <ul class="dropdown-menu" aria-labelledby="acercaDropdown">
              <li><a class="dropdown-item" href="acercade.php#empresa">Nuestra Empresa</a></li>
              <li><a class="dropdown-item" href="acercade.php#equipo">Nuestro Equipo</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="searchDropdown"><i class="fa-solid fa-magnifying-glass"></i></a>
            <div class="search-overlay" id="searchOverlay">
              <div class="search-container">
                <input type="text" class="form-control" placeholder="Buscar productos, categorías...">
                <div class="search-suggestions">
                  <a href="#">Juguetes populares</a>
                  <a href="#">Ropa infantil</a>
                  <a href="#">Accesorios</a>
                </div>
              </div>
            </div>
          </li>

<li class="nav-item dropdown iconos position-relative">
  <?php if(isset($_SESSION['usuario_id'])): ?>
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
      <i class="fa-solid fa-user mr-2"></i> <?= htmlspecialchars($_SESSION['nombre']) ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
      <li><a class="dropdown-item" href="perfil.php">Mi Sesión CubeStore</a></li>
      <li><a class="dropdown-item" href="perfiles/cerrar_sesion.php">Cerrar Sesión</a></li>
    </ul>
  <?php else: ?>
    <a class="nav-link" href="perfiles/iniciosesion.php"><i class="fa-solid fa-user mr-2"></i>Iniciar sesión</a>
  <?php endif; ?>
</li>

          <li class="nav-item">
  <a href="wishlist.php" class="nav-link">
    <i class="fa-solid fa-heart"></i>
  </a>
</li>
          <li class="nav-item iconos position-relative">
  <a class="nav-link" href="carrito.php">
    <i class="fa-solid fa-bag-shopping"></i>
    <?php
      if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0):
        $total = 0;
        foreach($_SESSION['carrito'] as $c){ $total += $c['cantidad']; }
    ?>
      <span id="contador-carrito" style="position:absolute; background:#ff4747; color:white; font-size:0.75rem; border-radius: 100px; padding:0px; text-align: center; width: 18px; height: 18px; top:5px; right:-5px; font-weight:bold;">
        <?= $total ?>
      </span>
    <?php endif; ?>
  </a>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero-about">
    <div class="hero-content">
      <h1>Más que una tienda, una sonrisa en cada compra</h1>
      <p class="lead mt-3">En CubeStore combinamos diversión, estilo y confianza para acompañar a cada niño en su crecimiento.</p>
    </div>
  </section>

  <!-- CONTENIDO -->
  <section class="py-5 bg-light text-center">
    <div class="container">
      <h2 class="fw-bold mb-4">Nuestra Historia</h2>
      <p class="lead text-muted mx-auto" style="max-width: 800px;">
        CubeStore nació del deseo de crear una tienda donde los niños puedan explorar su imaginación.  
        Cada producto es seleccionado con cariño, buscando siempre calidad, seguridad y una sonrisa al abrir cada paquete.
      </p>
    </div>
  </section>

  <!-- EQUIPO -->
  <section class="py-5">
    <div class="container text-center">
      <h2 class="fw-bold mb-5">Nuestro Equipo</h2>
      <div class="row g-5 justify-content-center">
        <div class="col-md-4">
          <img src="img/froilan.jpg" class="rounded-circle mb-3" width="130" alt="Fundador">
          <h5 class="fw-semibold">Froilan Aguilar Gamero</h5>
          <p class="text-muted">Fundador y Desarrollador</p>
        </div>
        <div class="col-md-4">
          <img src="img/yael.jpg" class="rounded-circle mb-3" width="130" alt="Diseñadora">
          <h5 class="fw-semibold">Yael Roman Reyes Rendon</h5>
          <p class="text-muted">Diseñadora Creativa</p>
        </div>
        <div class="col-md-4">
          <img src="img/deylan.jpg" class="rounded-circle mb-3" width="130" alt="Conserje">
          <h5 class="fw-semibold">Deylan Nieto Figueroa</h5>
          <p class="text-muted">Conserje de Planta</p>
        </div>
          <div class="col-md-4">
          <img src="img/alex1.jpg" class="rounded-circle mb-3" width="130" alt="Soporte tecnico">
          <h5 class="fw-semibold">Alex Fernando Salmeron Gonzalez</h5>
          <p class="text-muted">Soporte tecnico</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
  <div class="footer-content">
    <p class="text">Copyright © 2025 CubeStore Inc. Todos los derechos reservados.</p>
    <ul class="menu">
      <li class="menu-elem"><a href="" class="menu-icon">Aviso legal</a></li>
      <li>|</li>
      <li class="menu-elem"><a href="" class="menu-icon">Políticas de privacidad</a></li>
      <li>|</li>
      <li class="menu-elem"><a href="" class="menu-icon">Condiciones de compra</a></li>
    </ul>
    <div class="footer-line"></div>
  </div>
</footer>
<script>
  // Animación secuencial de secciones
  const sections = document.querySelectorAll('.hero-about, .py-5, .row.g-5');
  sections.forEach((sec, index) => {
      sec.style.animationDelay = `${index * 0.3}s`; // retraso entre cada sección
  });
</script>
<script src="../ComercioE2/Configuraciones/busqueda.js"></script>
<script src="Configuraciones/funciones.js"></script>
</body>
</html>
