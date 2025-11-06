<?php
session_start();
include 'Configuraciones/conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_SESSION['carrito'])) {
        echo "<p>No hay productos en el carrito.</p>";
        exit;
    }

    $usuario_id = $_SESSION['usuario_id'];
    $carrito = $_SESSION['carrito'];
    $direccion_id = intval($_POST['direccion_id']);

    // Calcular totales
    $subtotal = 0;
    foreach ($carrito as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
    }
    $envio = ($subtotal > 0) ? 50 : 0;
    $iva = $subtotal * 0.16;
    $total = $subtotal + $envio + $iva;

    $sql_pedido = "INSERT INTO pedidos (usuario_id, fecha, subtotal, envio, iva, total, direccion_id)
                   VALUES ($usuario_id, NOW(), $subtotal, $envio, $iva, $total, $direccion_id)";

    if ($conex->query($sql_pedido)) {
        $pedido_id = $conex->insert_id;

        foreach ($carrito as $item) {
            $producto_nombre = $conex->real_escape_string($item['nombre']);
            $producto_precio = floatval($item['precio']);
            $cantidad = intval($item['cantidad']);
            $total_producto = $producto_precio * $cantidad;

            $sql_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_nombre, producto_precio, cantidad, total)
                            VALUES ($pedido_id, '$producto_nombre', $producto_precio, $cantidad, $total_producto)";
            $conex->query($sql_detalle);
        }

        $_SESSION['carrito'] = [];
        $_SESSION['compra_exitosa'] = true;
    } else {
        die("Error al registrar el pedido: " . $conex->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Confirmación de compra | CubeStore</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilo.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" type="module"></script> 
<style>
  .footer {
  position: absolute;
  bottom: 0;
  width: 100vw;
}
.contenido{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  width: 100vw;
  height: 85vh;
}
.contenido a {
  background: var(--primary-color);
  color: #fff;
}
</style>
</head>
<body class="text-center mt-5">
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
              <li><a class="dropdown-item" href="catalogo.php?categoria=adultos">Adultos</a></li>
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

          <li class="nav-item iconos"><a class="nav-link" href="perfiles/iniciosesion.php"><i class="fa-solid fa-user"></i></a></li>
          <li class="nav-item iconos"><a class="nav-link" href="#"><i class="fa-solid fa-heart"></i></a></li>
          <li class="nav-item iconos position-relative">
  <a class="nav-link" href="carrito.php">
    <i class="fa-solid fa-bag-shopping"></i>
    <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
<span id="contador-carrito" class="contador-carrito">
        <?php 
          $total = 0;
          foreach($_SESSION['carrito'] as $c){ $total += $c['cantidad']; }
          echo $total;
        ?>
      </span>
    <?php endif; ?>
  </a>
</li>

        </ul>
      </div>
    </div>
  </nav>
  <section class="contenido">
    <h1>🎉 ¡Gracias por tu compra!</h1>
    <p>Tu pedido ha sido confirmado con éxito.</p>
    <a href="catalogo.php" class="mt-3 btn">Volver al catálogo</a>
  </section>

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
</body>
</html>
