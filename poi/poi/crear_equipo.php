<?php
include 'back-end/conex.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_grupo = trim($_POST['nombre_grupo']);

    if (empty($nombre_grupo)) {
        $error = "El nombre del equipo es obligatorio.";
    } else {
        $foto_contenido = null;

        if (isset($_FILES['foto_grupo']) && $_FILES['foto_grupo']['error'] === UPLOAD_ERR_OK) {
            $foto_tmp = $_FILES['foto_grupo']['tmp_name'];
            $foto_contenido = file_get_contents($foto_tmp);
        }

        $stmt = $conexion->prepare("INSERT INTO grupos (nombre_grupo, id_maestro, foto_grupo) VALUES (?, ?, ?)");
        $stmt->bind_param("sib", $nombre_grupo, $id_usuario, $foto_contenido);
        $stmt->send_long_data(2, $foto_contenido); // Campo binario

        if ($stmt->execute()) {
            header("Location: equipos.php");
            exit();
        } else {
            $error = "Error al crear el equipo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Equipo</title>
    <link rel="stylesheet" href="css/equipos.css">
</head>
<body id="main-body">

<?php include 'navtop.php'; ?>
<?php include 'navleft.php'; ?>

<div id="main-container">
    <h1 style="color: #fff; margin-bottom: 30px;">Crear Nuevo Equipo</h1>

    <?php if (!empty($error)) { ?>
        <p style="color: #ffb3b3; background: #3a3a5c; padding: 10px 15px; border-radius: 10px;">
            <?php echo $error; ?>
        </p>
    <?php } ?>

    <form method="POST" action="crear_equipo.php" enctype="multipart/form-data" style="width: 100%; max-width: 400px;">
        <div style="margin-bottom: 20px; text-align: left;">
            <label for="nombre_grupo" style="color: #f4f4f4;">Nombre del equipo:</label><br>
            <input type="text" id="nombre_grupo" name="nombre_grupo" required
                   style="width: 100%; padding: 10px; font-size: 1em; border-radius: 5px; border: none; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 20px; text-align: left;">
            <label for="foto_grupo" style="color: #f4f4f4;">Imagen del equipo:</label><br>
            <input type="file" id="foto_grupo" name="foto_grupo" accept="image/*"
                   style="color: #f4f4f4; background-color: #6c678d; border-radius: 5px; padding: 8px; margin-top: 5px;">
        </div>

        <button type="submit"
            style="background-color: #32325c; color: white; padding: 10px 20px; border: none; border-radius: 5px;
                   font-size: 1em; cursor: pointer; transition: background 0.3s ease;">
            Crear Equipo
        </button>
    </form>

    <a href="equipos.php" style="margin-top: 20px; color: #ddd; display: inline-block;">← Volver a Equipos</a>
</div>

</body>
</html>
