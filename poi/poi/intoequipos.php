<?php
session_start();
include 'back-end/conex.php';

if (!isset($_GET['id_grupo'])) {
    die("ID de grupo no especificado.");
}

$id_grupo = $_GET['id_grupo'];
$id_usuario_actual = $_SESSION['id_usuario'] ?? null;

// Obtener datos del grupo
$query = "SELECT * FROM grupos WHERE id_grupo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$resultado = $stmt->get_result();
$grupo = $resultado->fetch_assoc();
$id_maestro = $grupo['id_maestro'];

// Obtener canales
$query_canales = "SELECT * FROM canales WHERE id_grupo = ?";
$stmt = $conexion->prepare($query_canales);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$resultado_canales = $stmt->get_result();

// Obtener miembros
$query_miembros = "SELECT u.id_usuario, u.nombre FROM usuarios u 
                   JOIN miembros m ON u.id_usuario = m.id_usuario 
                   WHERE m.id_grupo = ?";
$stmt = $conexion->prepare($query_miembros);
$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$resultado_miembros = $stmt->get_result();
$miembros = $resultado_miembros->fetch_all(MYSQLI_ASSOC);
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
        <div id="left-section">
            <h3>Canales</h3>
            <ul id="lista-canales">
                <?php while ($canal = $resultado_canales->fetch_assoc()): ?>
                    <li onclick="seleccionarCanal(<?php echo $canal['id_canal']; ?>, '<?php echo htmlspecialchars($canal['nombre_canal']); ?>')">
                        <?php echo htmlspecialchars($canal['nombre_canal']); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div id="right-section">
            <div id="botones-acciones">
                <?php if ($id_usuario_actual == $id_maestro): ?>
                    <button onclick="abrirModal('modalMiembro')">Agregar Miembro</button>
                    <button onclick="abrirModal('modalCanal')">Agregar Canal</button>
                <?php endif; ?>
                <button onclick="abrirModal('modalMiembros')">Ver Miembros</button>
            </div>

            <div id="contenido-canal">
                <h3 id="titulo-canal">Selecciona un canal</h3>
                <div id="posts">
                    <p>Selecciona un canal para ver publicaciones.</p>
                </div>
                <form id="form-publicar" action="back-end/publicar.php" method="POST" style="display:none;">
                    <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                    <input type="hidden" name="id_canal" id="input-id-canal">
                    <input type="text" name="mensaje" placeholder="Escribe tu publicación..." required>
                    <button type="submit">Publicar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modales -->
    <div id="modalMiembro" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalMiembro')">&times;</span>
            <h3>Agregar Miembro</h3>
            <form action="back-end/procesar_equipo.php" method="POST">
                <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                <input type="text" name="username" placeholder="Nombre de usuario" required>
                <button type="submit" name="agregar_miembro">Agregar</button>
            </form>
        </div>
    </div>

    <div id="modalCanal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalCanal')">&times;</span>
            <h3>Agregar Canal</h3>
            <form action="back-end/procesar_equipo.php" method="POST">
                <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                <input type="text" name="nombre_canal" placeholder="Nombre del canal" required>
                <button type="submit" name="agregar_canal">Agregar</button>
            </form>
        </div>
    </div>

    <div id="modalMiembros" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalMiembros')">&times;</span>
            <h3>Miembros del grupo</h3>
            <ul>
                <?php foreach ($miembros as $miembro): ?>
                    <li><?php echo htmlspecialchars($miembro['nombre']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        function abrirModal(id) {
            document.getElementById(id).style.display = "block";
        }

        function cerrarModal(id) {
            document.getElementById(id).style.display = "none";
        }

        function seleccionarCanal(id_canal, nombre) {
            document.getElementById("titulo-canal").textContent = nombre;
            document.getElementById("input-id-canal").value = id_canal;
            document.getElementById("form-publicar").style.display = "block";

            const postsDiv = document.getElementById("posts");
            postsDiv.innerHTML = "<p>Cargando publicaciones del canal <strong>" + nombre + "</strong>...</p>";

            fetch('back-end/cargar_publicaciones.php?id_canal=' + id_canal)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        postsDiv.innerHTML = "<p>No hay publicaciones en este canal.</p>";
                        return;
                    }

                    postsDiv.innerHTML = "";
                    data.forEach(pub => {
                        postsDiv.innerHTML += `
                            <div class="publicacion">
                                <strong>${pub.nombre}</strong> <small>${pub.fecha_envio}</small>
                                <p>${pub.mensaje}</p>
                                <hr>
                            </div>`;
                    });
                })
                .catch(err => {
                    postsDiv.innerHTML = "<p>Error al cargar publicaciones.</p>";
                    console.error(err);
                });
        }
    </script>
</body>
</html>
