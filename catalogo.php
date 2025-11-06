<?php
session_start();
include '../ComercioE2/Configuraciones/conexion.php'; // primero la conexión

// --- Producto seleccionado para modal ---
$productoSeleccionado = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$detalleProducto = null;

if($productoSeleccionado){
    $stmtDetalle = $conex->prepare("SELECT * FROM Catalogo WHERE NomProducto = ?");
    $stmtDetalle->bind_param("s", $productoSeleccionado);
    $stmtDetalle->execute();
    $resDetalle = $stmtDetalle->get_result();
    $detalleProducto = $resDetalle->fetch_assoc();
    $stmtDetalle->close();
}

// --- Categoría ---
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : "";
$mapa_categoria = [
    "bebes" => "Para Bebés (0-2 años)",
    "infantil" => "Infantes",
    "adulto" => "Para Jóvenes adultos"
];
$filtro = isset($mapa_categoria[$categoria]) ? $mapa_categoria[$categoria] : "";

// --- Consulta de productos ---
if($filtro){
    $stmt = $conex->prepare("SELECT id_Producto, NomProducto, DesProducto, Precio, ImgProducto, CatEdad FROM Catalogo WHERE CatEdad = ?");
    $stmt->bind_param("s", $filtro);
} else {
    $stmt = $conex->prepare("SELECT id_Producto, NomProducto, DesProducto, Precio, ImgProducto, CatEdad FROM Catalogo");
}

$stmt->execute();
$resultado = $stmt->get_result();
$productos = [];
while($fila = $resultado->fetch_assoc()){
    $productos[] = $fila;
}
// --- Cargar favoritos del usuario ---
$favoritosUsuario = [];
if (isset($_SESSION['usuario_id'])) {
    $uid = $_SESSION['usuario_id'];
    $favQuery = $conex->prepare("SELECT producto_id FROM favoritos WHERE usuario_id = ?");
    $favQuery->bind_param("i", $uid);
    $favQuery->execute();
    $resFav = $favQuery->get_result();
    while ($row = $resFav->fetch_assoc()) {
        $favoritosUsuario[] = $row['producto_id'];
    }
    $favQuery->close();
}

$stmt->close();
$conex->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo | CubeStore</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilo.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
main{
    flex:1;
    max-width:1200px;
    margin:auto;
    padding:8rem 1rem 2rem;
}
.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #ff8479;
    margin-bottom: 2rem;
}
.filter-bar {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 3rem;
}
.filter-bar a {
    padding: 0.8rem 1.8rem;
    border-radius: 50px;
    font-weight: 600;
    background: #e0e5ff;
    color: #333;
    text-decoration: none;
    transition: 0.3s, transform 0.2s;
}
.filter-bar a.active,
.filter-bar a:hover {
    background: #d86e64ff;
    color: #fff;
    transform: translateY(-2px);
}
.productos-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:2rem;
}
.producto-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    display:flex;
    flex-direction:column;
    transition:transform 0.2s, box-shadow 0.2s;
    opacity:0; /* animación inicial */
    cursor: pointer;
}
.producto-card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}
.producto-card img{
    width:100%;
    height:200px;
    object-fit:cover;
}
.card-body{
    padding:1rem;
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.card-body form {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
}

.card-body h4{
    margin:0 0 0.5rem 0;
    font-size:1.1rem;
}
.card-body p{
    margin:0 0 0.5rem 0;
    color:#555;
    font-size:0.95rem;
}
.card-body span{
    font-weight:700;
    color:#0078ff;
    margin-bottom:0.5rem;
    display:block;
}
.card-body button{
    padding:0.5rem;
    border:none;
    border-radius:8px;
    background:#e0e5ff ;
    color:#000;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    border: 2px solid #000;
    margin: auto;
}
.card-body button:hover{
    background:#d86e64ff; 
    color: #fff;
    transform:translateY(-2px);
}
/* Animación fade-up */
@keyframes fadeUp {
    0% { opacity:0; transform:translateY(20px); }
    100% { opacity:1; transform:translateY(0); }
}
.producto-card{ animation: fadeUp 0.5s forwards; }

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

.modal-footer button {
  background: #e0e5ff;
  color: #000;
  outline: 2px solid #000;
}

.modal-footer button:hover {
  background: #d86e64ff;
  color: #fff;
}

.contador-carrito.animate {
  transform: scale(1.3);
}

/* Estilos del Modal - Profesional y Divertido */
.modal-content {
  border-radius:8px;
  border: none;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  overflow: hidden;
}

.modal-header {
  background: linear-gradient(135deg, #ff8479 0%, #ff8479 100%);
  border-bottom: none;
  padding: 1.5rem 2rem;
  position: relative;
}

.modal-header::after {
  content: '✨';
  position: absolute;
  right: 60px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1.5rem;
  opacity: 0.7;
}

.modal-title {
  color: #fff;
  font-size: 1.6rem;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin: 0;
}

.modal-header .btn-close {
  filter: brightness(0) invert(1);
  opacity: 0.8;
  transition: opacity 0.3s;
}

.modal-header .btn-close:hover {
  opacity: 1;
}

.modal-body {
  padding: 2rem;
  background: #fafbff;
}

#modalProductoImg {
  max-width: 300px;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s;
}

.modal-body > div {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

#modalProductoCat {
  display: inline-block;
  background: #e0e5ff;
  color: #5a67d8;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
  margin: 0;
  width: fit-content;
}

#modalProductoCat::before {
  content: '🏷️ ';
  margin-right: 0.3rem;
}

#modalProductoDesc {
  background: #fff;
  padding: 1.2rem;
  border-left: 4px solid #ff8479;
  color: #555;
  font-size: 1rem;
  line-height: 1.6;
  margin: 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  min-height: 80px;
}

#modalProductoDesc::before {
  content: '📝 Descripción';
  display: block;
  font-weight: 700;
  color: #ff8479;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

#modalProductoPrecio {
  font-weight: 800;
  font-size: 2rem;
  color: #0078ff;
  margin-top: auto;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

/* #modalProductoPrecio::before {
  content: ' 💰';
  font-size: 1.5rem;
} */

.modal-footer {
  background: #f8f9fa;
  border-top: 1px solid #e9ecef;
  padding: 1.5rem 2rem;
  gap: 1rem;
}

.modal-footer .btn {
  padding: 0.75rem 2rem;
  border-radius:8px;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s;
  border: none;
}



/* Responsive */
@media (max-width: 768px) {
  .modal-body {
    flex-direction: column;
  }
  
  #modalProductoImg {
    max-width: 100%;
    margin: 0 auto;
  }
  
  .modal-title {
    font-size: 1.3rem;
  }
  
  #modalProductoPrecio {
    font-size: 1.6rem;
  }
}

/* Animación de entrada del modal */
.modal.fade .modal-dialog {
  transform: scale(0.8);
  opacity: 0;
  transition: all 0.3s ease-out;
}

.modal.show .modal-dialog {
  transform: scale(1);
  opacity: 1;
}
.invisible {
  display: none;
}

.espacio {
  margin-right: 0.5rem;
}

.espacio_icono {
  margin-right: 0.5rem;
}

.btn-fav {
  background-color: #e0e5ff; /* fondo azul claro */
  border: none;
  color: #777; /* color inicial del ícono */
  cursor: pointer;
  border-radius: 8px; /* opcional, esquinas suaves */
  transition: background-color 0.2s ease, color 0.2s ease;
}

/* Al pasar el mouse */
.btn-fav:hover {
  background-color: #c7ceff; /* un poco más oscuro */
}

/* Cuando está en favoritos */
.btn-fav.favorito {
  color: #e63946; /* corazón rojo */
  background-color: #e0e5ff; /* mantiene el fondo azul claro */
}

/* Evita efectos de clic de Bootstrap */
.btn-fav:focus, .btn-fav:active {
  background-color: #e0e5ff !important;
  box-shadow: none !important;
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

          <li class="nav-item iconos position-relative">
  <a href="wishlist.php" class="nav-link">
    <i class="fa-solid fa-heart"></i>
    <?php
      // Contador de favoritos
      $totalFav = isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0;
      if($totalFav > 0):
    ?>
      <span id="contador-wishlist" style="position:absolute; background:#ff4747; color:white; font-size:0.75rem; border-radius: 100px; padding:0px; text-align: center; width: 18px; height: 18px; top:5px; right:-5px; font-weight:bold;">
        <?= $totalFav ?>
      </span>
    <?php endif; ?>
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
<h2 class="section-title">Catálogo</h2>

<div class="filter-bar">
    <a href="catalogo.php" class="<?php if(!$categoria) echo 'active'; ?>">Todos</a>
    <a href="catalogo.php?categoria=bebes" class="<?php if($categoria=='bebes') echo 'active'; ?>">Bebés</a>
    <a href="catalogo.php?categoria=infantil" class="<?php if($categoria=='infantil') echo 'active'; ?>">Infantil</a>
    <a href="catalogo.php?categoria=adulto" class="<?php if($categoria=='adulto') echo 'active'; ?>">Adulto</a>
</div>

<div class="productos-grid">
<?php if(count($productos) > 0): ?>
    <?php foreach($productos as $p): ?>
    <div class="producto-card" data-des="<?php echo htmlspecialchars($p['DesProducto']); ?>" data-id="<?php echo htmlspecialchars($p['id_Producto']); ?>">
        <img src="<?php echo htmlspecialchars($p['ImgProducto']); ?>" alt="<?php echo htmlspecialchars($p['NomProducto']); ?>">
        <div class="card-body">
            <h4><?php echo htmlspecialchars($p['NomProducto']); ?></h4>
            <p class="invisible"><?php echo htmlspecialchars($p['CatEdad']); ?></p>
            <span>$<?php echo number_format($p['Precio'], 0, '.', '.'); ?></span>
            <form class="form-agregar-carrito">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id_Producto']); ?>">
                <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($p['NomProducto']); ?>">
                <input type="hidden" name="precio" value="<?php echo htmlspecialchars($p['Precio']); ?>">
                <input type="hidden" name="imagen" value="<?php echo htmlspecialchars($p['ImgProducto']); ?>">
                <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($p['CatEdad']); ?>">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit">Añadir al carrito</button>
                <!-- Corazón para wishlist -->
<?php
$esFavorito = in_array($p['id_Producto'], $favoritosUsuario);
?>
<button type="button" class="btn-fav <?php echo $esFavorito ? 'favorito' : ''; ?>" data-id="<?php echo $p['id_Producto']; ?>">
  <i class="<?php echo $esFavorito ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
</button>


            </form>
        </div>
    </div>
<?php endforeach; ?>

<?php else: ?>
    <p style="text-align:center; font-size:1.2rem;">No hay productos en esta categoría.</p>
<?php endif; ?>
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

<script src="Configuraciones/funciones.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // Helper: convierte "$1.234" -> 1234 (entero)
  const precioANumero = (str) => {
    if(!str) return 0;
    return parseInt(String(str).replace(/\s/g,'').replace(/\$/g,'').replace(/\./g,'')) || 0;
  };

  // --- Agregar al carrito desde la card ---
  document.querySelectorAll('.form-agregar-carrito').forEach(form => {
      form.addEventListener('submit', async e => {
          e.preventDefault();
          e.stopPropagation();

          // Nos aseguramos que exista 'cantidad'
          if(!form.querySelector('input[name="cantidad"]')){
            const inputCant = document.createElement('input');
            inputCant.type = 'hidden';
            inputCant.name = 'cantidad';
            inputCant.value = 1;
            form.appendChild(inputCant);
          }

          const datos = new FormData(form);
          const res = await fetch('agregar_carrito.php', { method: 'POST', body: datos });
          const data = await res.json();

          // Actualizar contador carrito
          const icono = document.querySelector('.fa-bag-shopping');
          if(icono){
              let contador = document.querySelector('#contador-carrito');
              if(!contador){
                  contador = document.createElement('span');
                  contador.id = 'contador-carrito';
                  contador.classList.add('contador-carrito');
                  icono.parentElement.style.position = 'relative';
                  icono.parentElement.appendChild(contador);
              }
              contador.textContent = data.total;
              contador.classList.add('animate');
              setTimeout(()=>contador.classList.remove('animate'),200);
          }

          // Feedback visual al usuario
          const btn = form.querySelector('button[type="submit"]');
          if(btn){
            btn.textContent = "Añadido ✅";
            btn.style.background = "#8ccf84";
            setTimeout(() => {
                btn.textContent = "Añadir al carrito";
                btn.style.background = "";
            }, 1000);
          }
      });
  });

  // --- Modal al hacer click en la card ---
  document.querySelectorAll('.producto-card').forEach(card => {
      card.addEventListener('click', e => {
          if(e.target.closest('form')) return; // evita abrir modal si el clic fue en el botón

          const modalEl = document.getElementById('modalProducto');
          const modal = new bootstrap.Modal(modalEl);
          const nombre = card.querySelector('h4').textContent;
          const cat = card.querySelector('p').textContent;
          const precio = card.querySelector('span').textContent;
          const img = card.querySelector('img').src;
          const desc = card.dataset.des || "";
          const id = card.dataset.id || '';

          document.getElementById('modalProductoLabel').textContent = nombre;
          document.getElementById('modalProductoImg').src = img;
          document.getElementById('modalProductoImg').alt = nombre;
          document.getElementById('modalProductoCat').textContent = "Categoría: " + cat;
          document.getElementById('modalProductoDesc').textContent = desc;
          document.getElementById('modalProductoPrecio').textContent = precio;

          // Guardar id en atributo del modal para usar al añadir
          modalEl.dataset.productId = id;
          modal.show();
      });
  });

  // --- Animación secuencial de aparición de las cards ---
  const cards = document.querySelectorAll('.producto-card');
  cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.05}s`;
  });

  // --- Añadir al carrito desde modal (usa dataset.productId) ---
  const btnModal = document.querySelector('#modalProducto .btn-success');
  if(btnModal){
    btnModal.addEventListener('click', async () => {
      const modalEl = document.getElementById('modalProducto');
      const id = modalEl.dataset.productId || '';
      const nombre = document.getElementById('modalProductoLabel').textContent;
      const precioTxt = document.getElementById('modalProductoPrecio').textContent;
      const precio = precioANumero(precioTxt);
      const img = document.getElementById('modalProductoImg').src;
      const cat = document.getElementById('modalProductoCat').textContent.replace('Categoría: ','');
      const cantidad = 1;

      const datos = new FormData();
      if(id) datos.append('id', id);
      datos.append('nombre', nombre);
      datos.append('precio', precio);
      datos.append('imagen', img);
      datos.append('categoria', cat);
      datos.append('cantidad', cantidad);

      const res = await fetch('agregar_carrito.php', { method: 'POST', body: datos });
      const data = await res.json();

      // Actualizar contador carrito
      const icono = document.querySelector('.fa-bag-shopping');
      if(icono){
          let contador = document.querySelector('#contador-carrito');
          if(!contador){
              contador = document.createElement('span');
              contador.id = 'contador-carrito';
              contador.classList.add('contador-carrito');
              icono.parentElement.style.position = 'relative';
              icono.parentElement.appendChild(contador);
          }
          contador.textContent = data.total;
          contador.classList.add('animate');
          setTimeout(()=>contador.classList.remove('animate'),200);
      }

      // Feedback
      btnModal.textContent = "Añadido ✅";
      btnModal.style.background = "#8ccf84";
      setTimeout(() => {
          btnModal.textContent = "Añadir al carrito";
          btnModal.style.background = "";
      }, 1000);
    });
  }

});
</script>
<script>
document.querySelectorAll('.btn-fav').forEach(btn => {
    btn.addEventListener('click', async () => {
        const productoId = btn.dataset.id;
        const usuarioId = <?= isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null'; ?>;

        if (!usuarioId) {
            alert("Debes iniciar sesión para usar favoritos");
            return;
        }

        const formData = new FormData();
        formData.append('usuario_id', usuarioId);
        formData.append('producto_id', productoId);

        const res = await fetch('toggle_favorito.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        const icono = btn.querySelector('i');
        if (data.status === 'added') {
            btn.classList.add('favorito');
            icono.classList.remove('fa-regular');
            icono.classList.add('fa-solid');
        } else if (data.status === 'removed') {
            btn.classList.remove('favorito');
            icono.classList.remove('fa-solid');
            icono.classList.add('fa-regular');
        }
    });
});
</script>

<script src="../ComercioE2/Configuraciones/busqueda.js"></script>
<!-- Modal de producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProductoLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body d-flex flex-column flex-md-row gap-3">
        <img id="modalProductoImg" src="" alt="" class="img-fluid rounded" style="max-width:300px;">
        <div>
          <p id="modalProductoDesc"></p>
          <p id="modalProductoCat"></p>
          <span id="modalProductoPrecio" style="font-weight:bold; font-size:1.2rem;"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success">Añadir al carrito</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
