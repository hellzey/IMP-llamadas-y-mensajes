<?php
include 'conex.php';

session_start();
$id_usuario = $_SESSION['id_usuario']; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_amistad = $_POST['id_amistad'];
    $action = $_POST['action'];

    if ($action == "aceptar") {
        $query = "UPDATE Amistades SET estado = 'aceptada' WHERE id_amistad = ? AND id_receptor = ?";
    } elseif ($action == "rechazar") {
        $query = "DELETE FROM Amistades WHERE id_amistad = ? AND id_receptor = ?";
    } else {
        echo "Acción no válida.";
        exit;
    }

    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $id_amistad, $id_usuario);

    if ($stmt->execute()) {
        echo "Solicitud de amistad " . ($action == "aceptar" ? "aceptada" : "rechazada") . " correctamente.";
    } else {
        echo "Error al procesar la solicitud.";
    }
}
?>
