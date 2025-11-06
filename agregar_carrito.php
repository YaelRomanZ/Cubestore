<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Recoger datos (sanitiza lo básico)
$id = isset($_POST['id']) ? trim($_POST['id']) : null;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$precio = isset($_POST['precio']) ? floatval(str_replace(',', '.', $_POST['precio'])) : 0;
$imagen = isset($_POST['imagen']) ? trim($_POST['imagen']) : '';
$categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
if($cantidad < 1) $cantidad = 1;

// Inicializar carrito en sesión si no existe
if(!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Si se recibió id, usarlo para buscar; si no, intentar por nombre (menos fiable)
$foundIndex = null;
if($id !== null && $id !== '') {
    foreach($_SESSION['carrito'] as $index => $item) {
        if( isset($item['id']) && strval($item['id']) === strval($id) ) {
            $foundIndex = $index;
            break;
        }
    }
} else {
    // Fallback: comparar por nombre (normalmente no recomendable)
    foreach($_SESSION['carrito'] as $index => $item) {
        if( isset($item['nombre']) && $item['nombre'] === $nombre ) {
            $foundIndex = $index;
            break;
        }
    }
}

if($foundIndex !== null) {
    // Incrementar cantidad del item existente
    $_SESSION['carrito'][$foundIndex]['cantidad'] = intval($_SESSION['carrito'][$foundIndex]['cantidad']) + $cantidad;

    // Opcional: si precio cambió, actualizar (decisión de negocio)
    $_SESSION['carrito'][$foundIndex]['precio'] = $precio;
} else {
    // Agregar nuevo item
    $nuevo = [
        'id' => $id,
        'nombre' => $nombre,
        'precio' => $precio,
        'imagen' => $imagen,
        'categoria' => $categoria,
        'cantidad' => $cantidad
    ];
    $_SESSION['carrito'][] = $nuevo;
}

// Calcular total (suma de cantidades)
$total = 0;
foreach($_SESSION['carrito'] as $it) {
    $total += intval($it['cantidad']);
}

// Responder JSON (útil para actualizar contador)
echo json_encode([
    'ok' => true,
    'total' => $total,
    'carrito_count' => count($_SESSION['carrito'])
], JSON_UNESCAPED_UNICODE);
exit;
