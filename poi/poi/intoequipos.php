<?php
session_start();
include 'back-end/conex.php';

if (!isset($_GET['id_grupo'])) {
    die("ID de grupo no especificado.");
}

$id_grupo = $_GET['id_grupo'];

// Agregar publicación
if (isset($_POST['publicar'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $mensaje = $_POST['mensaje'];
    $id_canal = $_POST['id_canal'];

    $query_publicar = "INSERT INTO publicaciones (id_usuario, mensaje, id_grupo, id_canal) VALUES (?, ?, ?, ?)";
    $stmt_publicar = $conexion->prepare($query_publicar);
    $stmt_publicar->bind_param("isii", $id_usuario, $mensaje, $id_grupo, $id_canal);
    $stmt_publicar->execute();

    header("Location: intoequipos.php?id_grupo=$id_grupo&id_canal=$id_canal");
    exit();
}

// Info del grupo
$query = "SELECT * FROM grupos WHERE id_grupo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$resultado = $stmt->get_result();
$grupo = $resultado->fetch_assoc();

// Canales
$query_canales = "SELECT * FROM canales WHERE id_grupo = ?";
$stmt_canales = $conexion->prepare($query_canales);
$stmt_canales->bind_param("i", $id_grupo);
$stmt_canales->execute();
$resultado_canales = $stmt_canales->get_result();

// Miembros
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
        <!-- Lado izquierdo -->
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

        <!-- Lado derecho -->
        <div id="right-section">
            <!-- Miembros -->
            <div id="miembros">
                <h3>Miembros del grupo</h3>
                <ul>
                    <?php while ($miembro = $resultado_miembros->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($miembro['nombre']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Canales como botones -->
            <div id="canales">
                <h3>Canales</h3>
                <?php
                $stmt_canales->execute(); // reiniciar resultado
                $resultado_canales = $stmt_canales->get_result();
                ?>
                <form method="GET" action="intoequipos.php">
                    <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                    <?php while ($canal = $resultado_canales->fetch_assoc()): ?>
                        <button type="submit" name="id_canal" value="<?php echo $canal['id_canal']; ?>">
                            <?php echo htmlspecialchars($canal['nombre_canal']); ?>
                        </button>
                    <?php endwhile; ?>
                </form>
            </div>

            <!-- Publicaciones -->
            <div id="content">
                <h2>Publicaciones</h2>
                <?php
                $id_canal_seleccionado = isset($_GET['id_canal']) ? $_GET['id_canal'] : null;
                if ($id_canal_seleccionado):
                ?>
                    <!-- Formulario para publicar -->
                    <form action="intoequipos.php?id_grupo=<?php echo $id_grupo; ?>&id_canal=<?php echo $id_canal_seleccionado; ?>" method="POST">
                        <textarea name="mensaje" rows="4" placeholder="Escribe tu mensaje..." required></textarea>
                        <input type="hidden" name="id_canal" value="<?php echo $id_canal_seleccionado; ?>">
                        <button type="submit" name="publicar">Publicar</button>
                    </form>

                    <!-- Mostrar publicaciones -->
                    <div id="posts">
                        <?php
                        $query_publicaciones = "SELECT p.mensaje, p.fecha_envio, u.nombre AS usuario 
                                                FROM publicaciones p
                                                JOIN usuarios u ON p.id_usuario = u.id_usuario
                                                WHERE p.id_grupo = ? AND p.id_canal = ?
                                                ORDER BY p.fecha_envio DESC";
                        $stmt_publicaciones = $conexion->prepare($query_publicaciones);
                        $stmt_publicaciones->bind_param("ii", $id_grupo, $id_canal_seleccionado);
                        $stmt_publicaciones->execute();
                        $resultado_publicaciones = $stmt_publicaciones->get_result();

                        if ($resultado_publicaciones->num_rows > 0):
                            while ($publicacion = $resultado_publicaciones->fetch_assoc()):
                        ?>
                                <div class="post">
                                    <strong><?php echo htmlspecialchars($publicacion['usuario']); ?>:</strong>
                                    <p><?php echo htmlspecialchars($publicacion['mensaje']); ?></p>
                                    <small><?php echo $publicacion['fecha_envio']; ?></small>
                                </div>
                        <?php
                            endwhile;
                        else:
                            echo "<p>No hay publicaciones en este canal.</p>";
                        endif;
                        ?>
                    </div>
                <?php else: ?>
                    <p>Selecciona un canal para ver sus publicaciones y publicar mensajes.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
