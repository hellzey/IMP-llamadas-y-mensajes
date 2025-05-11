<?php
include 'conex.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_tarea = $_POST['id_tarea'];

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    $archivo = file_get_contents($_FILES['archivo']['tmp_name']);
    $nombre = $_FILES['archivo']['name'];

    // Obtener puntos de la tarea
    $stmt = $conexion->prepare("SELECT puntos_totales FROM tareas WHERE id_tarea = ?");
    $stmt->bind_param("i", $id_tarea);
    $stmt->execute();
    $stmt->bind_result($puntos);
    $stmt->fetch();
    $stmt->close();

    // Insertar entrega con puntos obtenidos
    $stmt = $conexion->prepare("INSERT INTO entregas (id_tarea, id_alumno, archivo_pdf, nombre_archivo, puntos_obtenidos) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $id_tarea, $id_usuario, $archivo, $nombre, $puntos);
    $stmt->execute();

    // Sumar puntos al usuario
    $stmt = $conexion->prepare("UPDATE usuarios SET puntos = puntos + ? WHERE id_usuario = ?");
    $stmt->bind_param("ii", $puntos, $id_usuario);
    $stmt->execute();

    header("Location: ../tareas.php?entregado=1");
} else {
    header("Location: ../tareas.php?error=1");
}
