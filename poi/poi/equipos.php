<?php
session_start();
include 'back-end/conex.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener solo los grupos donde el usuario es el maestro o es miembro
$query = "
    SELECT DISTINCT g.id_grupo, g.nombre_grupo, g.foto_grupo 
    FROM grupos g
    LEFT JOIN miembros m ON g.id_grupo = m.id_grupo
    WHERE g.id_maestro = ? OR m.id_usuario = ?
";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $id_usuario, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos</title>
    <link rel="stylesheet" href="css/equipos.css">
</head>
<body id="main-body">

<?php include 'navtop.php'; ?>
<?php include 'navleft.php'; ?>

<div id="main-container">
    <a href="crear_equipo.php" class="crear-equipo-btn">+ Crear un equipo</a>

    <div class="equipos">
        <h1>Equipos</h1>
        <div class="equipos-container">
            <?php while ($equipo = $resultado->fetch_assoc()): ?>
                <div class="equipo">
                    <?php if (!empty($equipo['foto_grupo'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($equipo['foto_grupo']); ?>" alt="Imagen del equipo">
                    <?php else: ?>
                        <img src="media/ado1.jpg" alt="Sin imagen">
                    <?php endif; ?>

                    <h2><?php echo htmlspecialchars($equipo['nombre_grupo']); ?></h2>
                    <p>Grupo ID: <?php echo $equipo['id_grupo']; ?></p>
                    <a href="intoequipos.php?id_grupo=<?php echo $equipo['id_grupo']; ?>">Ver más</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

</body>
</html>
