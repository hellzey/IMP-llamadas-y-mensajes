<?php
include 'conex.php';
session_start();

header("Content-Type: application/json"); 

$id_usuario = $_SESSION['id_usuario'];
$friend_id = $_GET['friend_id'];

$query = "
    SELECT id_emisor, contenido, fecha_envio 
    FROM mensajes
    WHERE (id_emisor = ? AND id_receptor = ?) OR (id_emisor = ? AND id_receptor = ?)
    ORDER BY fecha_envio ASC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iiii", $id_usuario, $friend_id, $friend_id, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "contenido" => $row["contenido"],
        "tipo" => ($row["id_emisor"] == $id_usuario) ? "sent" : "received"
    ];
}

echo json_encode($messages); 
?>
