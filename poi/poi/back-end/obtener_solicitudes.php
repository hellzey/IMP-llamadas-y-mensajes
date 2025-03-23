<?php
include 'conex.php';

session_start();
$id_usuario = $_SESSION['id_usuario']; 

$query = "
    SELECT a.id_amistad, u.id_usuario, u.nombre, u.foto_perfil 
    FROM Amistades a
    JOIN usuarios u ON a.id_remitente = u.id_usuario
    WHERE a.id_receptor = ? AND a.estado = 'pendiente'";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li>';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['foto_perfil']) . '" alt="Solicitud" class="friend-image">';
        echo htmlspecialchars($row['nombre']);
        echo ' <button onclick="manageFriendRequest(' . $row['id_amistad'] . ', \'aceptar\')">Aceptar</button>';
        echo ' <button onclick="manageFriendRequest(' . $row['id_amistad'] . ', \'rechazar\')">Rechazar</button>';
        echo '</li>';
    }
} else {
    echo '<li>No tienes solicitudes de amistad pendientes.</li>';
}
?>
