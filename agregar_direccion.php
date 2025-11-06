<?php
session_start();
include 'Configuraciones/conexion.php';

if(!isset($_SESSION['usuario_id'])){
    header("Location: perfiles/iniciosesion.php");
    exit;
}

$mensaje = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $usuario_id = $_SESSION['usuario_id'];
    $calle = $conex->real_escape_string($_POST['calle']);
    $ciudad = $conex->real_escape_string($_POST['ciudad']);
    $estado = $conex->real_escape_string($_POST['estado']);
    $cp = $conex->real_escape_string($_POST['cp']);

    $sql = "INSERT INTO direcciones (usuario_id, calle, ciudad, estado, cp) VALUES ($usuario_id, '$calle', '$ciudad', '$estado', '$cp')";
    if($conex->query($sql)){
        $mensaje = "Dirección agregada correctamente.";
        header("Location: carrito.php"); // redirige al carrito para seleccionar la nueva dirección
        exit;
    } else {
        $mensaje = "Error al agregar la dirección.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Dirección | CubeStore</title>
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
    background: linear-gradient(135deg, #f8f9fa, #ffe6f1);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100vw;
}
.card {
    width: 100%;
    max-width: 400px;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    background: white;
}
.btn-direccion {
    background: var(--primary-color);
    color: #fff;
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
<div class="card">
    <h2 class="text-center mb-3">Agregar nueva dirección</h2>
    <?php if($mensaje) echo "<div class='alert alert-info'>$mensaje</div>"; ?>
    <form method="POST">
        <input type="text" name="calle" class="form-control mb-2" placeholder="Calle y número" required>
        <input type="text" name="ciudad" class="form-control mb-2" placeholder="Ciudad" required>
        <input type="text" name="estado" class="form-control mb-2" placeholder="Estado" required>
        <input type="text" name="cp" class="form-control mb-2" placeholder="Código postal" required>
        <button type="submit" class="btn btn-direccion w-100">Guardar dirección</button>
    </form>
    <a href="carrito.php" class="btn btn-link mt-2 d-block text-center">Volver al carrito</a>
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
