<?php
session_start();
include 'conex.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no autenticado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_grupo = $_POST['id_grupo'] ?? null;
    $id_canal = $_POST['id_canal'] ?? null;
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (!$id_grupo || !$id_canal || empty($mensaje)) {
        die("Faltan datos.");
    }

    $query = "INSERT INTO publicaciones (id_usuario, id_grupo, id_canal, mensaje, fecha_envio)
              VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iiis", $id_usuario, $id_grupo, $id_canal, $mensaje);

    if ($stmt->execute()) {
        header("Location: ../intoequipos.php?id_grupo=$id_grupo");
        exit();
    } else {
        echo "Error al publicar: " . $stmt->error;
    }
}
?>
