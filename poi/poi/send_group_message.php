<?php
include 'back-end/conex.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar que se reciben los datos necesarios
if (!isset($_POST['group_id']) || !isset($_POST['message']) || empty(trim($_POST['message']))) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos']);
    exit();
}

$id_grupo = intval($_POST['group_id']);
$mensaje = trim($_POST['message']);

// Verificar que el grupo existe
$query = "SELECT COUNT(*) as existe FROM grupo_chat WHERE id_grupo_chat = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['existe'] == 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'El grupo no existe']);
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
    echo json_encode(['success' => false, 'error' => 'No eres miembro de este grupo']);
    exit();
}

// Insertar el mensaje
$query = "INSERT INTO mensajes_grupo (id_grupo_chat, id_emisor, contenido) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iis", $id_grupo, $id_usuario, $mensaje);

if ($stmt->execute()) {
    $id_mensaje = $conexion->insert_id;
    
    // Obtener el mensaje recién insertado con información adicional
    $query = "
        SELECT m.id_mensaje, m.id_emisor, u.nombre as nombre_emisor, m.contenido, m.fecha_envio
        FROM mensajes_grupo m
        JOIN usuarios u ON m.id_emisor = u.id_usuario
        WHERE m.id_mensaje = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_mensaje);
    $stmt->execute();
    $result = $stmt->get_result();
    $mensaje_enviado = $result->fetch_assoc();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => $mensaje_enviado]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Error al enviar el mensaje: ' . $stmt->error]);
}
exit();
?>