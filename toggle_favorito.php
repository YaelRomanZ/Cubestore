<?php
session_start();
include 'Configuraciones/conexion.php';

$usuario_id = $_POST['usuario_id'] ?? null;
$producto_id = $_POST['producto_id'] ?? null;

if(!$usuario_id || !$producto_id){
    echo json_encode(['status'=>'error']);
    exit;
}

// Ver si ya esta
$stmt = $conex->prepare("SELECT * FROM favoritos WHERE usuario_id=? AND producto_id=?");
$stmt->bind_param("ii", $usuario_id, $producto_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // Ya existe -> eliminar
    $stmtDel = $conex->prepare("DELETE FROM favoritos WHERE usuario_id=? AND producto_id=?");
    $stmtDel->bind_param("ii", $usuario_id, $producto_id);
    $stmtDel->execute();
    echo json_encode(['status'=>'removed']);
} else {
    // No existe -> agregar
    $stmtIns = $conex->prepare("INSERT INTO favoritos (usuario_id, producto_id) VALUES (?,?)");
    $stmtIns->bind_param("ii", $usuario_id, $producto_id);
    $stmtIns->execute();
    echo json_encode(['status'=>'added']);
}
?>
