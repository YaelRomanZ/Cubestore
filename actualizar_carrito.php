<?php
session_start();

// Validar datos recibidos
if (!isset($_POST['index'], $_POST['cantidad'])) {
    header('Location: carrito.php');
    exit;
}

$index = intval($_POST['index']);
$cantidad = intval($_POST['cantidad']);
if ($cantidad < 1) $cantidad = 1;

// Verificar que exista el carrito y el índice
if (isset($_SESSION['carrito']) && isset($_SESSION['carrito'][$index])) {
    // Asegurar tipos
    $_SESSION['carrito'][$index]['cantidad'] = $cantidad;
}

// Marcar para evitar la animación en la siguiente carga
$_SESSION['sin_animacion'] = true;

// Volver al carrito
header('Location: carrito.php');
exit;
?>
