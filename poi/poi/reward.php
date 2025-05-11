<?php
session_start();
include 'back-end/conex.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciose.php");
    exit();
}

$puntos_usuario = $_SESSION['puntos'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Recompensas.css">
    <title>Recompensas</title>
</head>
<body id="main-body">

    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <div id="main-container">
        <div class="Recompensas">
            <h1>Recompensas</h1>
            <p style="font-size: 1.2em; color: #333;"><strong>Tus puntos:</strong> <?php echo $puntos_usuario; ?></p>
            <div class="recompensas-container">
                <?php
                $sql = "SELECT id, imagen, precio FROM imagenes";
                $resultado = $conexion->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        $imagen = base64_encode($row['imagen']);
                        $id_recompensa = $row['id'];
                        echo '<div class="Recompensa">';
                        echo '<img src="data:image/png;base64,' . $imagen . '" alt="Recompensa ' . $id_recompensa . '">';
                        echo '<h2>Recompensa ' . $id_recompensa . '</h2>';
                        echo '<p><strong>Costo:</strong> ' . $row['precio'] . ' puntos</p>';

                        // Verificar si ya fue reclamada
                        $checkSql = "SELECT 1 FROM recompensas_usuario WHERE id_usuario = ? AND id_recompensa = ?";
                        $checkStmt = $conexion->prepare($checkSql);
                        $checkStmt->bind_param("ii", $id_usuario, $id_recompensa);
                        $checkStmt->execute();
                        $checkResult = $checkStmt->get_result();

                        if ($checkResult->num_rows > 0) {
                            echo '<p style="color: green;"><strong>Ya reclamado</strong></p>';
                        } else {
                            echo '<form method="post" action="reclamar_recompensa.php">';
                            echo '<input type="hidden" name="id_recompensa" value="' . $id_recompensa . '">';
                            echo '<input type="hidden" name="precio" value="' . $row['precio'] . '">';
                            echo '<button type="submit">Reclamar</button>';
                            echo '</form>';
                        }

                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay recompensas disponibles por ahora.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
