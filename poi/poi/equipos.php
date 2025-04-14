<?php
include 'back-end/conex.php';
session_start();

$query = "SELECT id_grupo, nombre_grupo, foto_grupo FROM grupos";
$resultado = $conexion->query($query);
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
                        <img src="media/placeholder.jpg" alt="Sin imagen"> <!-- Imagen por defecto -->
                    <?php endif; ?>
                    
                    <h2><?php echo htmlspecialchars($equipo['nombre_grupo']); ?></h2>
                    <p>Grupo ID: <?php echo $equipo['id_grupo']; ?></p> <!-- Puedes reemplazar esto con una descripción real -->
                    <a href="intoequipos.php?id=<?php echo $equipo['id_grupo']; ?>">Ver más</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

</body>
</html>
