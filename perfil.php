<?php
session_start();
include 'Configuraciones/conexion.php';

if(!isset($_SESSION['usuario_id'])){
    header("Location: perfiles/iniciosesion.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos del usuario
$usuario = $conex->query("SELECT nombre, apellido_paterno, apellido_materno, correo, telefono FROM usuarios WHERE id = $usuario_id")->fetch_assoc();

// Obtener historial de pedidos
$pedidos = $conex->query("SELECT * FROM pedidos WHERE usuario_id = $usuario_id ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Perfil | CubeStore</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilo.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" type="module"></script> 
<style>
    body {
        background: #f8f9fa;
        min-height: 100vh;
        padding-bottom: 80px;
    }

    li {
        font-weight: 400;
    }
    
    .footer {
        position: relative;
        bottom: 0;
        width: 100%;
        max-width: 100%;
    }
    
    .profile-header {
        background: white;
        border-radius: 15px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 2px 10px 12px -11px #00000059;
        text-align: center;
    }
    
    .profile-header h2 {
        color: #FF6F61;
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .profile-header p {
        color: #555;
        font-size: 1rem;
        margin: 0;
        font-weight: 300;
    }
    
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 35px;
        margin-bottom: 30px;
        box-shadow: 2px 10px 12px -11px #00000059;
    }
    
    .info-card h3 {
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 25px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .info-card h3 i {
        font-size: 1.3rem;
        color: #FF6F61;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 18px 20px;
        margin-bottom: 12px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background: #e0a1abff;
        transform: translateX(5px);
    }
    
    .info-item:hover i {
        color: white;
    }
    
    .info-item:hover strong,
    .info-item:hover span {
        color: white;
    }
    
    .info-item i {
        font-size: 1.3rem;
        color: #FF6F61;
        margin-right: 15px;
        width: 30px;
        text-align: center;
        transition: color 0.3s ease;
    }
    
    .info-item strong {
        color: #333;
        margin-right: 10px;
        font-weight: 600;
        min-width: 180px;
    }
    
    .info-item span {
        color: #555;
        font-size: 1rem;
        font-weight: 400;
    }
    
    .orders-section {
        background: white;
        border-radius: 15px;
        padding: 35px;
        box-shadow: 2px 10px 12px -11px #00000059;
    }
    
    .orders-section h3 {
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 25px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .orders-section h3 i {
        font-size: 1.3rem;
        color: #5cafe2ff;
    }
    
    .empty-orders {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-orders i {
        font-size: 4rem;
        color: #FFB6C1;
        margin-bottom: 20px;
    }
    
    .empty-orders p {
        font-size: 1.1rem;
        color: #999;
        margin-bottom: 25px;
        font-weight: 400;
    }
    
    .btn-shop {
        background: #FF6F61;
        color: white;
        padding: 12px 35px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-shop:hover {
        background: #ff5a4d;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 111, 97, 0.3);
        color: white;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }
    
    .orders-table thead th {
        background: #f8f9fa;
        color: #333;
        padding: 15px 18px;
        font-weight: 600;
        text-align: left;
        font-size: 0.95rem;
        border: none;
    }
    
    .orders-table thead th:first-child {
        border-radius: 10px 0 0 10px;
    }
    
    .orders-table thead th:last-child {
        border-radius: 0 10px 10px 0;
    }
    
    .orders-table tbody tr {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .orders-table tbody tr:hover {
        background: #fff;
        box-shadow: 2px 10px 12px -11px #00000059;
        transform: translateY(-2px);
    }
    
    .orders-table tbody td {
        padding: 18px;
        border: none;
        font-size: 0.95rem;
        color: #555;
    }
    
    .orders-table tbody tr td:first-child {
        border-radius: 10px 0 0 10px;
        font-weight: 600;
        color: #FF6F61;
    }
    
    .orders-table tbody tr td:last-child {
        border-radius: 0 10px 10px 0;
    }
    
    .btn-download {
        background: #5cafe2ff;
        color: white;
        padding: 8px 18px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.9rem;
    }
    
    .btn-download:hover {
        background: #5cafe2ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(135, 206, 250, 0.3);
        color: white;
    }
    
    .order-badge {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 15px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .badge-products {
        background: #FFD700;
        color: #856404;
    }
    
    .badge-total {
        background: #98FB98;
        color: #155724;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            padding: 25px 20px;
        }
        
        .profile-header h2 {
            font-size: 1.5rem;
        }
        
        .info-card, .orders-section {
            padding: 25px 20px;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .info-item strong {
            min-width: auto;
        }
        
        .orders-table {
            font-size: 0.8rem;
        }
        
        .orders-table thead th,
        .orders-table tbody td {
            padding: 10px 8px;
        }
        
        .btn-download {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
    }
</style>
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

<div class="container p-5" style="margin-top: 100px; padding-bottom: 50px;">
    <div class="profile-header">
        <h2>Hola, <?= htmlspecialchars($usuario['nombre']) ?> 👋</h2>
        <p>Bienvenido a tu espacio personal en CubeStore</p>
    </div>

    <div class="info-card">
        <h3><i class="fa-solid fa-user-circle"></i> Mi Información</h3>
        
        <div class="info-item">
            <i class="fa-solid fa-id-card"></i>
            <strong>Nombre completo:</strong>
            <span><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']) ?></span>
        </div>
        
        <div class="info-item">
            <i class="fa-solid fa-envelope"></i>
            <strong>Correo electrónico:</strong>
            <span><?= htmlspecialchars($usuario['correo']) ?></span>
        </div>
        
        <div class="info-item">
            <i class="fa-solid fa-phone"></i>
            <strong>Teléfono:</strong>
            <span><?= htmlspecialchars($usuario['telefono']) ?></span>
        </div>
    </div>

    <div class="orders-section">
        <h3><i class="fa-solid fa-box-open"></i> Historial de Pedidos</h3>
        
        <?php if($pedidos->num_rows === 0): ?>
            <div class="empty-orders">
                <i class="fa-solid fa-shopping-bag"></i>
                <p>Aún no has realizado ningún pedido</p>
                <!-- <a href="catalogo.php" class="btn-shop">
                    <i class="fa-solid fa-store"></i> Explorar Catálogo
                </a> -->
            </div>
        <?php else: ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th># Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Productos</th>
                        <th>Factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($pedido = $pedidos->fetch_assoc()): 
                        $pedido_id = $pedido['id'];
                        $detalle = $conex->query("SELECT COUNT(*) AS total_productos FROM detalle_pedidos WHERE pedido_id = $pedido_id")->fetch_assoc();
                    ?>
                    <tr>
                        <td>#<?= str_pad($pedido_id, 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= date('d/m/Y', strtotime($pedido['fecha'])) ?></td>
                        <td><span class="badge-total">$<?= number_format($pedido['total'],2) ?></span></td>
                        <td><span class="badge-products"><?= $detalle['total_productos'] ?> items</span></td>
                        <td>
                            <a href="generar_factura.php?pedido_id=<?= $pedido_id ?>" class="btn-download">
                                <i class="fa-solid fa-file-pdf"></i> Descargar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script src="Configuraciones/funciones.js"></script>
<script src="../ComercioE2/Configuraciones/busqueda.js"></script>

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
</body>
</html>