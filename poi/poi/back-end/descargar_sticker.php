<?php
include 'conex.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_imagen = $_GET['id'] ?? 0;

// Verificamos que esa imagen haya sido reclamada por el usuario
$sql = "SELECT imagen FROM recompensas_usuario 
        JOIN imagenes ON recompensas_usuario.id_recompensa = imagenes.id
        WHERE recompensas_usuario.id_usuario = ? AND imagenes.id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_imagen);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $imagen = $row['imagen'];
    header('Content-Type: image/jpeg');
    header('Content-Disposition: attachment; filename="sticker_' . $id_imagen . '.jpg"');
    echo $imagen;
} else {
    echo "No tienes permiso para descargar esta imagen.";
}
