<?php
include 'conex.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nombre_completo = trim($_POST['full-name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; 
    $fecha_nacimiento = $_POST['birthdate'];
    
    $foto_perfil = null;
    if (!empty($_FILES['profile-photo']['tmp_name'])) {
        $foto_perfil = file_get_contents($_FILES['profile-photo']['tmp_name']);
    }
    $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Error: El correo o nombre de usuario ya están en uso.";
        exit();
    }
    $stmt->close();

    $stmt = $conexion->prepare("INSERT INTO usuarios (username, nombre, correo, contra, fecha_nacimiento, foto_perfil) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $nombre_completo, $email, $password, $fecha_nacimiento, $foto_perfil);

    if ($stmt->execute()) {
        
        header("Location: ../iniciose.php"); 
        exit(); 
    } else {
        echo "Error al registrar usuario: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
