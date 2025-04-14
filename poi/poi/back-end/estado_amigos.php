<?php
include 'conex.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];

$query = "
    SELECT u.id_usuario, u.ultima_actividad
    FROM usuarios u
    JOIN Amistades a ON (a.id_remitente = u.id_usuario OR a.id_receptor = u.id_usuario)
    WHERE (a.id_remitente = ? OR a.id_receptor = ?)
    AND a.estado = 'aceptada'
    AND u.id_usuario != ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iii", $id_usuario, $id_usuario, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$amigos = [];

while ($row = $result->fetch_assoc()) {
    $amigos[] = [
        'id_usuario' => $row['id_usuario'],
        'ultima_actividad' => $row['ultima_actividad']
    ];
}

header('Content-Type: application/json');
echo json_encode($amigos);
