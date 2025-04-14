<?php
session_start();
include 'conex.php';
// Obtener id_grupo desde POST o GET
if (isset($_POST['id_grupo'])) {
    $id_grupo = $_POST['id_grupo'];
} elseif (isset($_GET['id_grupo'])) {
    $id_grupo = $_GET['id_grupo'];
} else {
    die("ID de grupo no especificado.");
}


// Agregar miembro
if (isset($_POST['agregar_miembro'])) {
    $username = $_POST['username'];

    // Buscar usuario por su nombre
    $query_usuario = "SELECT id_usuario FROM usuarios WHERE nombre = ?";
    $stmt_usuario = $conexion->prepare($query_usuario);
    $stmt_usuario->bind_param("s", $username);
    $stmt_usuario->execute();
    $resultado_usuario = $stmt_usuario->get_result();

    if ($resultado_usuario->num_rows > 0) {
        $usuario = $resultado_usuario->fetch_assoc();
        $id_usuario = $usuario['id_usuario'];

        // Verificar si ya es miembro
        $query_miembro_existente = "SELECT * FROM miembros WHERE id_grupo = ? AND id_usuario = ?";
        $stmt_miembro_existente = $conexion->prepare($query_miembro_existente);
        $stmt_miembro_existente->bind_param("ii", $id_grupo, $id_usuario);
        $stmt_miembro_existente->execute();
        $resultado_miembro_existente = $stmt_miembro_existente->get_result();

        if ($resultado_miembro_existente->num_rows == 0) {
            // Agregar como miembro
            $query_agregar_miembro = "INSERT INTO miembros (id_grupo, id_usuario) VALUES (?, ?)";
            $stmt_agregar_miembro = $conexion->prepare($query_agregar_miembro);
            $stmt_agregar_miembro->bind_param("ii", $id_grupo, $id_usuario);
            $stmt_agregar_miembro->execute();
        }
    }
    
    // Redirigir de vuelta
    header("Location: ../intoequipos.php?id_grupo=$id_grupo");
    exit();
}

// Agregar canal
if (isset($_POST['agregar_canal'])) {
    $nombre_canal = $_POST['nombre_canal'];
    
    $query_agregar_canal = "INSERT INTO canales (id_grupo, nombre_canal) VALUES (?, ?)";
    $stmt_agregar_canal = $conexion->prepare($query_agregar_canal);
    $stmt_agregar_canal->bind_param("is", $id_grupo, $nombre_canal);
    $stmt_agregar_canal->execute();

    // Redirigir de vuelta
    header("Location: ../intoequipos.php?id_grupo=$id_grupo");
    exit();
}
?>
