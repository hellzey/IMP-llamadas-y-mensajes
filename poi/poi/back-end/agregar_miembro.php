<?php
session_start();
require 'back-end/conex.php';

if (isset($_POST['agregar_miembro']) && isset($_POST['id_grupo'])) {
    $id_grupo = intval($_POST['id_grupo']);
    $username = trim($_POST['username']);

    // Buscar usuario por username
    $stmt_usuario = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
    $stmt_usuario->bind_param("s", $username);
    $stmt_usuario->execute();
    $res_usuario = $stmt_usuario->get_result();

    if ($res_usuario->num_rows > 0) {
        $id_usuario = $res_usuario->fetch_assoc()['id_usuario'];

        // Verificar si ya es miembro
        $stmt_check = $conexion->prepare("SELECT 1 FROM miembros WHERE id_grupo = ? AND id_usuario = ?");
        $stmt_check->bind_param("ii", $id_grupo, $id_usuario);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();

        if ($res_check->num_rows === 0) {
            // Agregar miembro
            $stmt_insert = $conexion->prepare("INSERT INTO miembros (id_grupo, id_usuario) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $id_grupo, $id_usuario);
            $stmt_insert->execute();
        }
    }

    // 🔄 Redirigir a la página del equipo con id_grupo
    header("Location: ../equipos.php");
    exit;
} else {
    // Error por parámetros faltantes
    header("Location: ../equipos.php");
    exit;
}
?>
