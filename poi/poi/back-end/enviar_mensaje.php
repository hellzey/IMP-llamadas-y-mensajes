<?php
include 'conex.php';
session_start();

header("Content-Type: application/json"); // Asegurarnos de que la respuesta sea JSON

$response = ["status" => "error", "message" => "Mensaje no enviado"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $friend_id = $_POST['friend_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $query = "INSERT INTO mensajes (id_emisor, id_receptor, contenido, fecha_envio) VALUES (?, ?, ?, NOW())";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iis", $id_usuario, $friend_id, $message);
        
        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Mensaje enviado"];
        } else {
            $response = ["status" => "error", "message" => "Error al guardar el mensaje"];
        }
    }
}

echo json_encode($response); // Enviar respuesta JSON
?>
