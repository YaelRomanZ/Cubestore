<?php
session_start();
include 'Configuraciones/conexion.php';

$usuario_id = $_SESSION['usuario_id'];

$result = $conexion->query("SELECT * FROM pedidos WHERE usuario_id = $usuario_id ORDER BY fecha DESC");

while($pedido = $result->fetch_assoc()){
    echo "Pedido #{$pedido['id']} - Total: {$pedido['total']} - Fecha: {$pedido['fecha']}<br>";
    
    $detalles = $conexion->query("SELECT * FROM detalle_pedidos WHERE pedido_id = {$pedido['id']}");
    while($d = $detalles->fetch_assoc()){
        echo "- {$d['producto_nombre']} x {$d['cantidad']} = {$d['total']}<br>";
    }
    echo "<hr>";
}


?>