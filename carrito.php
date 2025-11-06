<?php
session_start();
include 'Configuraciones/conexion.php'; // imprescindible

// Verificar si el usuario está loggeado
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login o mostrar mensaje
    header("Location: perfiles/iniciosesion.php");
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']); // seguro para SQL
$direcciones = $conex->query("SELECT * FROM direcciones WHERE usuario_id = $usuario_id");


// Detectar si debe evitar animación (por eliminación o actualización)
$sin_animacion = isset($_SESSION['sin_animacion']) && $_SESSION['sin_animacion'];
unset($_SESSION['sin_animacion']); // limpiar bandera

// Obtener el carrito
$carrito = isset($_SESSION["carrito"]) ? $_SESSION["carrito"] : [];

// Calcular subtotal y total de ítems
$subtotal = 0;
$totalItems = 0;

foreach ($carrito as $item) {
    $precio = isset($item["precio"]) ? floatval($item["precio"]) : 0;
    $cantidad = isset($item["cantidad"]) ? intval($item["cantidad"]) : 1;
    $subtotal += $precio * $cantidad;
    $totalItems += $cantidad;
}

// Envío fijo si hay productos
$envio = ($subtotal > 0) ? 50 : 0;

// IVA
$iva = $subtotal * 0.16; // 16%

// Total monetario
$totalMonetario = $subtotal + $envio + $iva;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Tu Carrito | CubeStore</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
<link rel="stylesheet" href="css/estilo.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>
html, body { height: 100%; margin:0; padding:0; }
body { display: flex; flex-direction: column; background: linear-gradient(135deg, #f8f9fa, #ffe6f1); }
main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 80px 0; min-height: calc(100vh - 150px); }

.cart-container {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(8px);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  width: 90%;
  max-width: 1100px;
  transition: transform 0.3s ease;
  opacity: <?php echo $sin_animacion ? '1' : '0'; ?>;
  transform: <?php echo $sin_animacion ? 'translateY(0)' : 'translateY(40px)'; ?>;
  <?php if (!$sin_animacion): ?>
  animation: floatIn 0.8s ease forwards;
  <?php endif; ?>
}

@keyframes floatIn {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

.espacio {
  margin-left: 0.5rem;
}

.espacio_icono {
  margin-right: 0.5rem;
}

.cart-container:hover { transform: scale(1.01); }
.cart-title {
  background: -webkit-linear-gradient(#ff0015, #db4691);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
  font-size: 2.3rem;
  text-align: center;
  margin-bottom: 2rem;
}
.cart-icon { font-size: 2rem; color: #db4691; margin-right: 10px; }
.btn-primary {
  background: linear-gradient(90deg, #4bbe51, #8bcc7b);
  border: none;
}
.btn-primary:hover { opacity: 0.9; }
footer { margin-top: auto; }

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
      <i class="fa-solid fa-user espacio_icono"></i> <?= htmlspecialchars($_SESSION['nombre']) ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
      <li><a class="dropdown-item" href="perfil.php">Mi Sesión CubeStore</a></li>
      <li><a class="dropdown-item" href="perfiles/cerrar_sesion.php">Cerrar Sesión</a></li>
    </ul>
  <?php else: ?>
    <a class="nav-link" href="perfiles/iniciosesion.php"><i class="fa-solid fa-user espacio"></i>Iniciar sesión</a>
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
<div class="cart-container">
  <h1 class="cart-title"><i class="fa-solid fa-bag-shopping cart-icon"></i>Tu Carrito</h1>
  <div class="row g-4">

    <!-- Productos -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <?php if(empty($carrito)): ?>
            <p class="text-center fs-5 my-4">Tu carrito está vacío 🛒</p>
          <?php else: ?>
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($carrito as $index => $item): 
                $precio = floatval($item['precio']);
                $cantidad = intval($item['cantidad']);
                $totalProducto = $precio * $cantidad;
              ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($item['imagen']); ?>" class="rounded me-3" width="60" alt="Producto">
                    <div>
                      <strong><?= htmlspecialchars($item['nombre']); ?></strong><br>
                      <small class="text-muted"><?= htmlspecialchars($item['categoria'] ?? ''); ?></small>
                    </div>
                  </div>
                </td>
                <td>$<?= number_format($precio,2) ?></td>
                <td>
                  <form action="actualizar_carrito.php" method="POST" class="d-inline">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="number" name="cantidad" class="form-control form-control-sm w-50 d-inline" value="<?= $cantidad ?>" min="1" onchange="this.form.submit()">
                  </form>
                </td>
                <td>$<?= number_format($totalProducto,2) ?></td>
                <td>
                  <form action="eliminar_carrito.php" method="POST">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button class="btn btn-sm btn-outline-danger">✕</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Resumen -->
<div class="col-lg-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="fw-semibold mb-3 text-center">Resumen de compra</h5>
      <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>$<?= number_format($subtotal,2) ?></span></div>
      <div class="d-flex justify-content-between mb-2"><span>Envío</span><span>$<?= number_format($envio,2) ?></span></div>
      <hr>
      <div class="d-flex justify-content-between mb-2"><span>IVA (16%)</span><span>$<?= number_format($iva,2) ?></span></div>
      <div class="d-flex justify-content-between fw-bold mb-3"><span>Total</span><span>$<?= number_format($totalMonetario,2) ?></span></div>

      <?php if($subtotal > 0): ?>
      <form action="confirmar_compra.php" method="POST">
        <label for="direccion_id" class="form-label">Dirección de envío:</label>
        <select name="direccion_id" id="direccion_id" class="form-select mb-2" required>
          <?php while($dir = $direcciones->fetch_assoc()): 
            $direccion_completa = $dir['calle'] . ', ' . $dir['ciudad'] . ', ' . $dir['estado'] . ' CP:' . $dir['cp'];
            $direccion_corta = (strlen($direccion_completa) > 50) 
              ? substr($direccion_completa, 0, 27) . '...' 
              : $direccion_completa;
          ?>
            <option value="<?= $dir['id'] ?>" title="<?= htmlspecialchars($direccion_completa); ?>">
              <?= htmlspecialchars($direccion_corta); ?>
            </option>
          <?php endwhile; ?>
        </select>
        <div class="text-center mb-2">
          <a href="agregar_direccion.php" class="btn btn-link p-0">Agregar nueva dirección</a>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3">Proceder al pago</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>


  </div>
</div>
</main>

<footer class="footer">
  <div class="footer-content text-center py-3">
    <p>Copyright © 2025 CubeStore Inc. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="../ComercioE2/Configuraciones/busqueda.js"></script>
<script src="Configuraciones/funciones.js"></script>
</body>
</html>
