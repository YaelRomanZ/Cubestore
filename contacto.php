<?php session_start(); ?>
php
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
    body {
      background: linear-gradient(135deg, #ffffff 0%, #f2f6fa 100%);
      color: #333;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 3rem 1rem;
    }

    .contact-container {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      padding: 2.5rem;
      max-width: 600px;
      width: 100%;
      text-align: center;
      animation: fadeIn 0.7s ease;
    }

    .contact-container h1 {
          color: var(--primary-color);
      margin-bottom: 1rem;
      font-size: 2rem;
    }

    .contact-container p {
      margin-bottom: 2rem;
      color: #555;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    input, textarea {
      width: 100%;
      padding: 0.9rem 1.2rem;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s ease;
      font-family: 'Poppins', sans-serif;
      resize: none;
    }

    input:focus, textarea:focus {
      border-color: #0078ff;
      outline: none;
      box-shadow: 0 0 0 3px rgba(0,120,255,0.1);
    }

    button {
      background: var(--primary-color);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 1rem;
      font-size: 1rem;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #ff8479;
      transform: translateY(-0.5px);
      box-shadow: 0 5px 15px rgba(0,120,255,0.3);
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


    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
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


  <main>
    <div class="contact-container">
      <h1>Contáctanos</h1>
      <p>¿Tienes alguna duda, sugerencia o simplemente quieres saludarnos?  
         Completa el siguiente formulario y te responderemos lo antes posible.</p>
      <form action="#" method="post">
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        <input type="email" name="correo" placeholder="Tu correo electrónico" required>
        <input type="text" name="asunto" placeholder="Asunto" required>
        <textarea name="mensaje" rows="5" placeholder="Tu mensaje..." required></textarea>
        <button type="submit">Enviar mensaje</button>
      </form>
    </div>
  </main>

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
<script src="../ComercioE2/Configuraciones/busqueda.js"></script>
<script src="Configuraciones/funciones.js"></script>
</body>
</html>
