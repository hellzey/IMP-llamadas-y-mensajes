<?php
include 'conex.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];
$friend_id = $_POST['friend_id'];
$message = trim($_POST['message']);

if (!empty($message)) {
    $query = "INSERT INTO mensajes (id_emisor, id_receptor, contenido) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iis", $id_usuario, $friend_id, $message);
    $stmt->execute();
}

?>
