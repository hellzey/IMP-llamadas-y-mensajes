<?php
include 'back-end/conex.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_grupo = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

if ($id_grupo <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID de grupo inválido']);
    exit();
}

// Verificar que el usuario es miembro del grupo
$query = "SELECT COUNT(*) as es_miembro FROM miembros_grupo_chat WHERE id_grupo_chat = ? AND id_usuario = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $id_grupo, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['es_miembro'] == 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No eres miembro de este grupo']);
    exit();
}

// Obtener los miembros del grupo
$query = "
    SELECT u.id_usuario, u.nombre, u.username
    FROM usuarios u
    JOIN miembros_grupo_chat m ON u.id_usuario = m.id_usuario
    WHERE m.id_grupo_chat = ?
    ORDER BY u.nombre ASC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();

$miembros = [];
while ($row = $result->fetch_assoc()) {
    $miembros[] = $row;
}

header('Content-Type: application/json');
echo json_encode($miembros);
exit();
?>