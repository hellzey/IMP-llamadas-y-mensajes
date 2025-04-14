<?php
session_start();
include 'back-end/conex.php';

// Verificar si se recibió el id_grupo por GET
if (!isset($_GET['id_grupo'])) {
    die("ID de grupo no especificado.");
}

$id_grupo = $_GET['id_grupo'];

// Obtener la información del grupo usando el ID
$query = "SELECT * FROM grupos WHERE id_grupo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$resultado = $stmt->get_result();
$grupo = $resultado->fetch_assoc();

// Obtener los canales del grupo
$query_canales = "SELECT * FROM canales WHERE id_grupo = ?";
$stmt_canales = $conexion->prepare($query_canales);
$stmt_canales->bind_param("i", $id_grupo);
$stmt_canales->execute();
$resultado_canales = $stmt_canales->get_result();

// Obtener miembros actuales del grupo
$query_miembros = "SELECT u.id_usuario, u.nombre FROM usuarios u 
                   JOIN miembros m ON u.id_usuario = m.id_usuario 
                   WHERE m.id_grupo = ?";
$stmt_miembros = $conexion->prepare($query_miembros);
$stmt_miembros->bind_param("i", $id_grupo);
$stmt_miembros->execute();
$resultado_miembros = $stmt_miembros->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@equipo</title>
    <link rel="stylesheet" href="css/intoequipo.css">
</head>
<body id="main-body"> 
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <div class="container" id="main-container">
        <!-- Lado izquierdo: Agregar Miembros y Canales -->
        <div id="left-section">
            <h3>Agregar Miembro</h3>
            <form action="back-end/procesar_equipo.php" method="POST">
                <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" placeholder="Escribe el nombre de usuario" required>
                <button type="submit" name="agregar_miembro">Agregar</button>
            </form>

            <h3>Agregar Canal</h3>
            <form action="back-end/procesar_equipo.php" method="POST">
                <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                <input type="text" name="nombre_canal" placeholder="Nombre del canal" required>
                <button type="submit" name="agregar_canal">Agregar Canal</button>
            </form>
        </div>

        <!-- Lado derecho (Centro y Derecho): Canales y Publicaciones -->
        <div id="right-section">
            <!-- Miembros del grupo -->
            <div id="miembros">
                <h3>Miembros del grupo</h3>
                <ul>
                    <?php while ($miembro = $resultado_miembros->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($miembro['nombre']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Canales del grupo -->
            <div id="canales">
                <h3>Canales</h3>
                <ul>
                    <?php while ($canal = $resultado_canales->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($canal['nombre_canal']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Publicaciones -->
            <div id="content">
                <h2>Publicaciones</h2>
                <div id="posts">
                    <p>Selecciona un canal para ver publicaciones.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPosts(channel) {
            let posts = {
                'general': '<p>Bienvenidos al canal General.</p>',
                'random': '<p>Publicaciones Random aquí.</p>',
                'noticias': '<p>Últimas noticias en este canal.</p>'
            };
            document.getElementById('posts').innerHTML = posts[channel] || '<p>No hay publicaciones.</p>';
        }
    </script>
</body>
</html>
