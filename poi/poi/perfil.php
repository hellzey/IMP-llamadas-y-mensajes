<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir si no está logueado
    exit();
}

// Obtener los datos del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];
$nombre = $_SESSION['nombre'] ?? '';
$username = $_SESSION['username'] ?? '';
$correo = $_SESSION['correo'] ?? ''; 
$foto_perfil = $_SESSION['foto_perfil'] ?? '';
$fecha_nacimiento = $_SESSION['fecha_nacimiento'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registro.css">
    <title>Perfil de Usuario</title>
    <script>
        function habilitarCampos() {
            document.getElementById("username").disabled = false;
            document.getElementById("full-name").disabled = false;
            document.getElementById("birthdate").disabled = false;
            document.getElementById("profile-photo").disabled = false;
        }

        function mostrarFoto(event) {
            const photoPreview = document.getElementById('current-profile-photo');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function guardarCambios(event) {
            event.preventDefault();
            alert('Cambios guardados correctamente');
        }
    </script>
</head>
<body id="main-body">
<?php include 'navtop.php'; ?>
<?php include 'navleft.php'; ?>
    <div class="container" id="main-container">
        <h1 id="main-heading">Mi Perfil</h1>
        <form id="profile-form" enctype="multipart/form-data" onsubmit="guardarCambios(event)">
            <!-- Campo de correo electrónico -->
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($correo); ?>" disabled>
            </div>
            
            <!-- Campo de nombre de usuario -->
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled required>
            </div>
            
            <!-- Campo de nombre completo -->
            <div class="form-group">
                <label for="full-name">Nombre Completo</label>
                <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($nombre); ?>" disabled required>
            </div>
            
            <!-- Campo de fecha de nacimiento -->
            <div class="form-group">
                <label for="birthdate">Fecha de Nacimiento</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" disabled required>
            </div>
            
            <!-- Campo de foto de perfil -->
            <div class="form-group">
                <label for="profile-photo">Foto de Perfil</label>
                <input type="file" id="profile-photo" name="profile-photo" accept="image/*" disabled onchange="mostrarFoto(event)">
                <img src="<?php echo !empty($foto_perfil) ? 'data:image/jpeg;base64,' . base64_encode($foto_perfil) : 'media/user.png'; ?>" 
                     alt="Foto de perfil" id="current-profile-photo" style="max-width: 150px; margin-top: 10px;">
            </div>

            <!-- Botón para habilitar los campos -->
            <button type="button" onclick="habilitarCampos()">Editar Perfil</button>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
