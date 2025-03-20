<?php
session_start();
include 'conex.php'; 

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Escapar las entradas para evitar inyecciones SQL
    $email = $conexion->real_escape_string($email);
    
    // Preparar la consulta
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, correo, contra, foto_perfil, username, fecha_nacimiento FROM usuarios WHERE correo = ?");
    
    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Se encontró el usuario
        $row = $result->fetch_assoc();
        
        // Comparación directa de contraseñas (sin hash)
        if ($password == $row['contra']) {
            // Login exitoso
            
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['correo'] = $row['correo'];
            $_SESSION['foto_perfil'] = !empty($row['foto_perfil']) ? base64_encode($row['foto_perfil']) : null;

            $_SESSION['username'] = $row['username'];
            $_SESSION['fecha_nacimiento'] = $row['fecha_nacimiento'];

            // Redirigir al perfil
            header("Location: ../perfil.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../iniciose.php?error=1");
            exit();
        }
    } else {
        // No se encontró el correo electrónico
        header("Location: ../iniciose.php?error=1");
        exit();
    }

    $stmt->close();
} else {
    echo "Por favor, completa todos los campos del formulario.";
}
?>