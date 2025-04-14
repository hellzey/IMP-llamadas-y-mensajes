<?php
include 'conex.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Validar que los datos existen
if (
    isset($_POST['id_grupo'], $_POST['titulo'], $_POST['descripcion'], 
    $_POST['fecha_entrega'], $_POST['puntos_totales'])
) {
    $id_grupo = intval($_POST['id_grupo']);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_entrega = $_POST['fecha_entrega'];
    $puntos = intval($_POST['puntos_totales']);

    // Verificar que el grupo pertenezca al maestro
    $verificar = $conexion->prepare("SELECT * FROM grupos WHERE id_grupo = ? AND id_maestro = ?");
    $verificar->bind_param("ii", $id_grupo, $id_usuario);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows === 0) {
        // El grupo no le pertenece al usuario
        echo "No tienes permiso para crear tareas en este grupo.";
        exit;
    }

    // Insertar tarea
    $stmt = $conexion->prepare("
        INSERT INTO tareas (id_grupo, titulo, descripcion, fecha_entrega, puntos_totales)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssi", $id_grupo, $titulo, $descripcion, $fecha_entrega, $puntos);

    if ($stmt->execute()) {
        header("Location: ../tareas.php?creada=1");
        exit;
    } else {
        echo "Error al crear la tarea.";
    }

} else {
    echo "Faltan datos del formulario.";
}
?>
    