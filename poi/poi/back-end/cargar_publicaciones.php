<?php
include 'conex.php';

if (!isset($_GET['id_canal'])) {
    echo json_encode([]);
    exit;
}

$id_canal = $_GET['id_canal'];

$query = "SELECT p.mensaje, p.fecha_envio, u.nombre
          FROM publicaciones p
          JOIN usuarios u ON p.id_usuario = u.id_usuario
          WHERE p.id_canal = ?
          ORDER BY p.fecha_envio DESC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_canal);
$stmt->execute();
$resultado = $stmt->get_result();

$publicaciones = [];

while ($row = $resultado->fetch_assoc()) {
    $publicaciones[] = $row;
}

echo json_encode($publicaciones);
