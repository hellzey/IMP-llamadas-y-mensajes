<?php
include 'conex.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_grupo = $_POST['id_grupo'];
    $nombre = $_POST['nombre_canal'];

    $stmt = $conexion->prepare("INSERT INTO canales (id_grupo, nombre_canal) VALUES (?, ?)");
    $stmt->bind_param("is", $id_grupo, $nombre);
    if ($stmt->execute()) {
        // Usar una ruta absoluta para la redirección
        header("Location: ../intoequipos.php?id_grupo=$id_grupo");
        exit();  // Asegurarse de que no se ejecute nada más después de la redirección
    } else {
        echo "Error al crear canal";
    }
}
?>
