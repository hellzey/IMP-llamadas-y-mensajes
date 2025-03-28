<?php
session_start();
require 'conex.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado");
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['full-name']);
    $username = trim($_POST['username']);
    $fecha_nacimiento = $_POST['birthdate'];
    

    $stmt_check = $conexion->prepare("SELECT nombre, username, fecha_nacimiento, foto_perfil FROM usuarios WHERE id_usuario = ?");
    $stmt_check->bind_param("i", $id_usuario);
    $stmt_check->execute();
    $stmt_check->bind_result($nombre_actual, $username_actual, $fecha_nacimiento_actual, $foto_actual);
    $stmt_check->fetch();
    $stmt_check->close();
    

    $foto = $foto_actual; 
    if (!empty($_FILES['profile-photo']['tmp_name'])) {
        $foto = file_get_contents($_FILES['profile-photo']['tmp_name']);
    }

 
    if ($nombre === $nombre_actual && $username === $username_actual && $fecha_nacimiento === $fecha_nacimiento_actual && $foto === $foto_actual) {
        echo "No hay cambios para actualizar.";
        exit();
    }

    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, username = ?, fecha_nacimiento = ?, foto_perfil = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nombre, $username, $fecha_nacimiento, $foto, $id_usuario);
    
    if ($stmt->execute()) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['username'] = $username;
        $_SESSION['fecha_nacimiento'] = $fecha_nacimiento;
        $_SESSION['foto_perfil'] = !empty($foto) ? base64_encode($foto) : $_SESSION['foto_perfil'];
        
        header("Location: ../perfil.php");
    } else {
        echo "Error al actualizar el perfil: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
