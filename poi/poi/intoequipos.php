<?php
include 'back-end/conex.php';
session_start();

if (!isset($_GET['id_grupo'])) {
    die("El grupo no está especificado.");
}

$id_grupo = $_GET['id_grupo']; // Obtener el ID del grupo desde la URL

// Obtener detalles del grupo
$query = "
    SELECT g.nombre_grupo, g.fecha_creacion, u.nombre AS maestro_nombre
    FROM grupos g
    JOIN usuarios u ON g.id_maestro = u.id_usuario
    WHERE g.id_grupo = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$grupo = $stmt->get_result()->fetch_assoc();

// Obtener miembros del grupo
$query_miembros = "
    SELECT u.nombre
    FROM miembros m
    JOIN usuarios u ON m.id_usuario = u.id_usuario
    WHERE m.id_grupo = ?";

$stmt_miembros = $conexion->prepare($query_miembros);
$stmt_miembros->bind_param("i", $id_grupo);
$stmt_miembros->execute();
$miembros = $stmt_miembros->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/equipos.css">
    <title>Detalles del Equipo</title>
</head>
<body>
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <div id="main-container">
        <h1>Detalles de <?php echo htmlspecialchars($grupo['nombre_grupo']); ?></h1>
        <p>Creado por: <?php echo htmlspecialchars($grupo['maestro_nombre']); ?></p>
        <p>Fecha de creación: <?php echo $grupo['fecha_creacion']; ?></p>

        <h2>Miembros</h2>
        <ul>
            <?php while ($miembro = $miembros->fetch_assoc()) { ?>
                <li><?php echo htmlspecialchars($miembro['nombre']); ?></li>
            <?php } ?>
        </ul>
    </div>

</body>
</html>
