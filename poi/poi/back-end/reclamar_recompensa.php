<?php
session_start();
include 'back-end/conex.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_recompensa'], $_POST['precio'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_recompensa = (int) $_POST['id_recompensa'];
    $precio = (int) $_POST['precio'];

    // Verificar puntos del usuario
    $stmt = $conexion->prepare("SELECT puntos FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    $puntos_usuario = $row['puntos'];

    // Verificar si ya la tiene
    $check = $conexion->prepare("SELECT 1 FROM recompensas_usuario WHERE id_usuario = ? AND id_recompensa = ?");
    $check->bind_param("ii", $id_usuario, $id_recompensa);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        header("Location: reward.php?error=ya_reclamada");
        exit();
    }

    if ($puntos_usuario >= $precio) {
        // Insertar en recompensas_usuario
        $insert = $conexion->prepare("INSERT INTO recompensas_usuario (id_usuario, id_recompensa) VALUES (?, ?)");
        $insert->bind_param("ii", $id_usuario, $id_recompensa);
        $insert->execute();

        // Restar puntos
        $update = $conexion->prepare("UPDATE usuarios SET puntos = puntos - ? WHERE id_usuario = ?");
        $update->bind_param("ii", $precio, $id_usuario);
        $update->execute();

        header("Location: reward.php?ok=1");
    } else {
        header("Location: reward.php?error=sin_puntos");
    }
} else {
    header("Location: reward.php");
}
?>
