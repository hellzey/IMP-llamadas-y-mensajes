<?php
include 'back-end/conex.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener tareas pendientes del usuario (alumno)
$query = "
    SELECT t.*, g.nombre_grupo 
    FROM tareas t
    JOIN grupos g ON t.id_grupo = g.id_grupo
    JOIN miembros m ON m.id_grupo = g.id_grupo
    WHERE m.id_usuario = ? AND t.estado = 'pendiente'
    ORDER BY t.fecha_entrega ASC
";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultadoTareas = $stmt->get_result();

// Verificar si el usuario es maestro de algún grupo
$stmtMaestro = $conexion->prepare("SELECT * FROM grupos WHERE id_maestro = ?");
$stmtMaestro->bind_param("i", $id_usuario);
$stmtMaestro->execute();
$gruposMaestro = $stmtMaestro->get_result();

$esMaestro = $gruposMaestro->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas</title>
    <link rel="stylesheet" href="css/tareas.css">
</head>
<body id="main-body">

<?php include 'navtop.php'; ?>
<?php include 'navleft.php'; ?>

<div class="container" id="main-container">
    <h1 id="main-heading">Tareas</h1>

    <?php if (isset($_GET['creada']) && $_GET['creada'] == 1): ?>
        <p style="color: green;">✅ Tarea creada correctamente.</p>
    <?php endif; ?>

    <?php if (isset($_GET['entregado']) && $_GET['entregado'] == 1): ?>
        <p style="color: green;">✅ Entrega realizada correctamente.</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red;">❌ Hubo un error al subir el archivo.</p>
    <?php endif; ?>

    <?php if ($esMaestro): ?>
        <div style="margin-bottom: 15px;">
            <button class="crear-btn" onclick="document.getElementById('modal-tarea').style.display='block'">
                Crear tarea
            </button>
            <a href="ver_entregas.php" class="crear-btn" style="text-decoration: none; margin-left: 10px;">
                Ver entregas
            </a>
        </div>
    <?php endif; ?>

    <div class="tareas-container">
        <?php while ($tarea = $resultadoTareas->fetch_assoc()): 
            $id_tarea = $tarea['id_tarea'];

            // Verificar si esta tarea ya fue entregada
            $consultaEntrega = $conexion->prepare("SELECT * FROM entregas WHERE id_tarea = ? AND id_alumno = ?");
            $consultaEntrega->bind_param("ii", $id_tarea, $id_usuario);
            $consultaEntrega->execute();
            $resultadoEntrega = $consultaEntrega->get_result();
            $entregada = $resultadoEntrega->num_rows > 0;
        ?>
            <div class="tarea">
                <span class="fecha"><?= date("M d, Y", strtotime($tarea['fecha_entrega'])) ?></span>
                <div class="tarea-info">
                    <div class="icono">📘</div>
                    <div>
                        <h2><?= htmlspecialchars($tarea['titulo']) ?></h2>
                        <p class="hora">Entrega: <?= date("g:i A", strtotime($tarea['fecha_entrega'])) ?></p>
                        <p class="curso"><?= htmlspecialchars($tarea['nombre_grupo']) ?></p>
                    </div>
                </div>

                <?php if (!$entregada): ?>
                    <form action="back-end/subir_entrega.php" method="POST" enctype="multipart/form-data" class="form-entrega">
                        <input type="hidden" name="id_tarea" value="<?= $id_tarea ?>">
                        <label>Subir PDF:</label>
                        <input type="file" name="archivo" accept="application/pdf" required>
                        <button type="submit">Entregar</button>
                    </form>
                <?php else: ?>
                    <p style="color: green;">✅ Entregada</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- MODAL CREAR TAREA -->
<?php if ($esMaestro): ?>
<div id="modal-tarea" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="document.getElementById('modal-tarea').style.display='none'">&times;</span>
        <h2>Crear nueva tarea</h2>
        <form action="back-end/crear_tarea.php" method="POST">
            <label for="id_grupo">Grupo:</label>
            <select name="id_grupo" required>
                <?php while ($grupo = $gruposMaestro->fetch_assoc()): ?>
                    <option value="<?= $grupo['id_grupo'] ?>"><?= htmlspecialchars($grupo['nombre_grupo']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" required><br><br>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" rows="3" required></textarea><br><br>

            <label for="fecha_entrega">Fecha de entrega:</label>
            <input type="date" name="fecha_entrega" required><br><br>

            <label for="puntos_totales">Puntos totales:</label>
            <input type="number" name="puntos_totales" min="1" required><br><br>

            <button type="submit" class="crear-btn">Guardar tarea</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    window.onclick = function(event) {
        const modal = document.getElementById('modal-tarea');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
