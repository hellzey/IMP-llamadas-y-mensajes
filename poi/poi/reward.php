<?php include 'back-end/conex.php'; ?>
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
            <div class="recompensas-container">
                <?php
                // Consulta para obtener las recompensas desde la base de datos
                $sql = "SELECT id, imagen, precio FROM imagenes";
                $resultado = $conexion->query($sql);

                // Verificar si hay resultados
                if ($resultado && $resultado->num_rows > 0) {
                    // Mostrar cada recompensa
                    while ($row = $resultado->fetch_assoc()) {
                        // Convertimos la imagen binaria a base64 para mostrarla en HTML
                        $imagen = base64_encode($row['imagen']);
                        echo '<div class="Recompensa">';
                        echo '<img src="data:image/jpeg;base64,' . $imagen . '" alt="Recompensa ' . $row['id'] . '">';
                        echo '<h2>Recompensa ' . $row['id'] . '</h2>';
                        echo '<p><strong>Costo:</strong> ' . $row['precio'] . ' puntos</p>';
                        echo '<a href="equipos.php">Ver más</a>';
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
