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

    $stmt = $conexion->prepare("INSERT INTO entregas (id_tarea, id_alumno, archivo_pdf, nombre_archivo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_tarea, $id_usuario, $archivo, $nombre);
    $stmt->execute();

    header("Location: ../tareas.php?entregado=1");
} else {
    header("Location: ../tareas.php?error=1");
}
