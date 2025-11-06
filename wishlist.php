<?php
session_start();
include 'Configuraciones/conexion.php';

// Verificar sesión
if(!isset($_SESSION['usuario_id'])){
    header('Location: perfiles/iniciosesion.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Consulta de productos favoritos
$stmt = $conex->prepare("
    SELECT 
        f.id AS id_favorito,
        c.id_Producto,
        c.NomProducto AS nombre,
        c.DesProducto AS descripcion,
        c.Precio AS precio,
        c.ImgProducto AS imagen,
        c.CatEdad AS categoria
    FROM favoritos f
    INNER JOIN Catalogo c ON f.producto_id = c.id_Producto
    WHERE f.usuario_id = ?
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$favoritos = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conex->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Favoritos | CubeStore</title>
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
main{
  flex:1;
  max-width:1200px;
  margin:auto;
  padding:8rem 1rem 2rem;
}
.section-title {
  text-align:center;
  font-size:2.5rem;
  font-weight:700;
  color:#ff8479;
  margin-bottom:2rem;
}

/* Rejilla de productos */
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
  animation: fadeUp 0.5s forwards;
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
.card-body h4{
  margin:0 0 0.5rem 0;
  font-size:1.1rem;
}
.card-body span{
  font-weight:700;
  color:#0078ff;
  margin-bottom:0.5rem;
  display:block;
}

/* Botón añadir al carrito */
.card-body button{
  padding:0.5rem;
  border:none;
  border-radius:8px;
  background:#e0e5ff;
  color:#000;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
  border:2px solid #000;
}
.card-body button:hover{
  background:#d86e64ff; 
  color:#fff;
  transform:translateY(-2px);
}

/* Botón eliminar favorito */
.btn-remove {
  position:absolute;
  bottom:17px;
  right:20px;
  background:#e0e5ff;
  border: 2px solid #000;
  color:#000;
  font-size:1.5rem;
  border-radius:8px;
  cursor:pointer;
  transition:all 0.2s ease;
}
.btn-remove:hover {
  background:#d86e64ff;
  color:#fff;
  transform:scale(1.1);
}

/* Animación fade-up */
@keyframes fadeUp {
  0% { opacity:0; transform:translateY(20px); }
  100% { opacity:1; transform:translateY(0); }
}

.footer {
  position: absolute;
  bottom: 0;
  width: 100vw;
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
<h2 class="section-title">Mis Favoritos ❤️</h2>

<div class="productos-grid pt-5">
<?php if(count($favoritos) > 0): ?>
  <?php foreach($favoritos as $f): ?>
    <div class="producto-card position-relative">
      <button class="btn-remove" data-id="<?= $f['id_Producto']; ?>"><i class="fa-solid fa-trash"></i></button>
      <img src="<?= htmlspecialchars($f['imagen']); ?>" alt="<?= htmlspecialchars($f['nombre']); ?>">
      <div class="card-body">
        <h4><?= htmlspecialchars($f['nombre']); ?></h4>
        <span>$<?= number_format($f['precio'], 2, '.', ','); ?></span>
        <form class="form-agregar-carrito">
          <input type="hidden" name="id" value="<?= $f['id_Producto']; ?>">
          <input type="hidden" name="nombre" value="<?= htmlspecialchars($f['nombre']); ?>">
          <input type="hidden" name="precio" value="<?= $f['precio']; ?>">
          <input type="hidden" name="imagen" value="<?= htmlspecialchars($f['imagen']); ?>">
          <input type="hidden" name="categoria" value="<?= htmlspecialchars($f['categoria']); ?>">
          <input type="hidden" name="cantidad" value="1">
          <button type="submit">Añadir al carrito</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p style="
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.2rem;
    text-align: center;
    color: #555;
">
    No tienes productos en favoritos.
</p>

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
<script>
// --- Eliminar favorito ---
document.querySelectorAll('.btn-remove').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.dataset.id;
    const usuarioId = <?= $_SESSION['usuario_id']; ?>;

    const formData = new FormData();
    formData.append('usuario_id', usuarioId);
    formData.append('producto_id', id);

    const res = await fetch('toggle_favorito.php', { method:'POST', body: formData });
    const data = await res.json();

    if(data.status === 'removed'){
      btn.closest('.producto-card').remove();
    }
  });
});
</script>
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
          if(e.target.closest('form')) return; // evita abrir modal

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
<script src="Configuraciones/funciones.js"></script>
<script src="../ComercioE2/Configuraciones/busqueda.js"></script>
</body>
</html>
