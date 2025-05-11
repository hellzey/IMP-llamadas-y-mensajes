<?php
include 'back-end/conex.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_maestro = $_SESSION['id_usuario'];

// Obtener tareas creadas por el maestro con info del grupo
$query = "
    SELECT t.id_tarea, t.titulo, g.nombre_grupo
    FROM tareas t
    JOIN grupos g ON t.id_grupo = g.id_grupo
    WHERE g.id_maestro = ?
    ORDER BY t.fecha_entrega DESC
";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_maestro);
$stmt->execute();
$resultadoTareas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Entregas</title>
    <link rel="stylesheet" href="css/tareas.css">
</head>
<body id="main-body">

<?php include 'navtop.php'; ?>
<?php include 'navleft.php'; ?>

<div class="container" id="main-container">
    <h1 id="main-heading">Entregas de Tareas</h1>

    <?php if ($resultadoTareas->num_rows === 0): ?>
        <p>No has creado tareas aún.</p>
    <?php else: ?>
        <?php while ($tarea = $resultadoTareas->fetch_assoc()): ?>
            <div class="tarea">
                <h2><?= htmlspecialchars($tarea['titulo']) ?> (<?= htmlspecialchars($tarea['nombre_grupo']) ?>)</h2>
                <ul>
                <?php
                    // Obtener entregas por tarea
                    $queryEntregas = "
                        SELECT e.id_entrega, e.fecha_entrega, u.nombre, u.username
                        FROM entregas e
                        JOIN usuarios u ON e.id_alumno = u.id_usuario
                        WHERE e.id_tarea = ?
                        ORDER BY e.fecha_entrega ASC
                    ";
                    $stmtEntregas = $conexion->prepare($queryEntregas);
                    $stmtEntregas->bind_param("i", $tarea['id_tarea']);
                    $stmtEntregas->execute();
                    $resultEntregas = $stmtEntregas->get_result();

                    if ($resultEntregas->num_rows === 0):
                        echo "<li>No hay entregas aún.</li>";
                    else:
                        while ($entrega = $resultEntregas->fetch_assoc()):
                ?>
                    <li>
                        🧑 <?= htmlspecialchars($entrega['nombre']) ?> (<?= $entrega['username'] ?>) - 📅 <?= $entrega['fecha_entrega'] ?> 
                        | <a href="back-end/descargar_entrega.php?id_entrega=<?= $entrega['id_entrega'] ?>">📥 Descargar PDF</a>
                    </li>
                <?php endwhile; endif; ?>
                </ul>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
