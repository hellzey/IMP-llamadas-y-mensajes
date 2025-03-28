<?php
include 'conex.php';

session_start();
$id_usuario = $_SESSION['id_usuario'];  
$username_or_email = $_POST['username_or_email']; 


if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
    $campo = 'correo';
} else {
    $campo = 'nombre';
}


$query = "SELECT id_usuario FROM usuarios WHERE $campo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $username_or_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
 
    $receptor = $result->fetch_assoc();
    $id_receptor = $receptor['id_usuario'];


    $query = "SELECT * FROM Amistades WHERE (id_remitente = ? AND id_receptor = ?) OR (id_remitente = ? AND id_receptor = ?) ";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iiii", $id_usuario, $id_receptor, $id_receptor, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Ya tienes una solicitud de amistad pendiente o ya eres amigo.";
    } else {
 
        $query = "INSERT INTO Amistades (id_remitente, id_receptor, estado) VALUES (?, ?, 'pendiente')";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $id_receptor);
        if ($stmt->execute()) {
            echo "Solicitud de amistad enviada correctamente.";
        } else {
            echo "Error al enviar la solicitud.";
        }
    }
} else {
    echo "El usuario no existe.";
}
?>
