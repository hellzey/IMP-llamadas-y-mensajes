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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <?php if ($esMaestro): ?>
        <button class="crear-btn" onclick="document.getElementById('modal-tarea').style.display='block'">
            Crear tarea
        </button>
    <?php endif; ?>

    <div class="tareas-container">
        <?php while ($tarea = $resultadoTareas->fetch_assoc()): ?>
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
    // Cierra el modal si se hace clic fuera de él
    window.onclick = function(event) {
        const modal = document.getElementById('modal-tarea');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
