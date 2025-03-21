<?php
include 'conex.php';

session_start();
$id_usuario = $_SESSION['id_usuario'];  // El usuario que está logueado
$username_or_email = $_POST['username_or_email'];  // El dato enviado desde el frontend

// Verifica si el dato recibido es un correo o un nombre de usuario
if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
    $campo = 'correo';
} else {
    $campo = 'nombre';
}

// Buscar al usuario por nombre o correo
$query = "SELECT id_usuario FROM usuarios WHERE $campo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $username_or_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // El usuario existe, obtenemos el id del receptor
    $receptor = $result->fetch_assoc();
    $id_receptor = $receptor['id_usuario'];

    // Verificar si ya existe una amistad pendiente o aceptada
    $query = "SELECT * FROM Amistades WHERE (id_remitente = ? AND id_receptor = ?) OR (id_remitente = ? AND id_receptor = ?) ";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iiii", $id_usuario, $id_receptor, $id_receptor, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Ya tienes una solicitud de amistad pendiente o ya eres amigo.";
    } else {
        // Si no existe, enviamos una solicitud de amistad
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
