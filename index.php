<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CubeStore</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilo.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" type="module"></script> 
</head>

<body>
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

          <?php session_start(); ?>
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

  <div id="mainContent">
    <div class="container" style="padding-top: 0px; padding-bottom: 50px;">
      <div id="carouselExample" class="carousel slide sombra" style="margin-top: 100px;">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="img/banner.png" class="d-block w-100 rounded-4" alt="carousel">
          </div>
          <div class="carousel-item">
            <img src="img/promocion_1.jpeg" class="d-block w-100" alt="imagen pokemon juguetes">
          </div>
          <div class="carousel-item">
            <img src="img/promocion_2.jpg" class="d-block w-100" alt="imagen placeholder">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>
    </div>

<div class="container text-center my-4">
  <h2 class="slogan">¡Descubre la diversión y estilo para todos!</h2>
  <p class="slogan-sub">Juguetes, juegos y accesorios que harán sonreír a grandes y pequeños</p>
</div>

<div class="container my-5">
  <div class="row g-4 text-center">
    <div class="col-md-4">
      <a href="catalogo.php?categoria=bebes" class="categoria-card">
        <div class="categoria-content">
          <img src="img/juguetes.png" alt="Bebés" class="categoria-img">
          <h3>Bebés</h3>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="catalogo.php?categoria=infantil" class="categoria-card">
        <div class="categoria-content">
          <img src="img/infantil.webp" alt="Ropa" class="categoria-img">
          <h3>Infantil</h3>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="catalogo.php?categoria=adulto" class="categoria-card">
        <div class="categoria-content">
          <img src="img/adultos.webp" alt="Accesorios" class="categoria-img">
          <h3>Adultos</h3>
        </div>
      </a>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="row text-center g-4">
    <div class="col-md-4">
      <div class="beneficio-card">
        <i class="fa-solid fa-truck fa-2xl"></i>
        <h4>Envío rápido</h4>
        <p>Recibe tus productos en tiempo récord</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="beneficio-card">
        <i class="fa-solid fa-gift fa-2xl"></i>
        <h4>Regalos divertidos</h4>
        <p>Ideas y detalles que encantarán</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="beneficio-card">
        <i class="fa-solid fa-check fa-2xl"></i>
        <h4>Garantía de calidad</h4>
        <p>Productos confiables y duraderos</p>
      </div>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="color-block-container">
    <div class="color-block block1" style="background-color:#FFB6C1;">Juega</div>
    <div class="color-block block2" style="background-color:#87CEFA;">Vístete</div>
    <div class="color-block block3" style="background-color:#FFD700;">Decora</div>
    <div class="color-block block4" style="background-color:#98FB98;">Aprende</div>
  </div>
</div>


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
<script src="Configuraciones/funciones.js"></script>
<script src="../ComercioE2/Configuraciones/busqueda.js"></script>
</body>
</html>