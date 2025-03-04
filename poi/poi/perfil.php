<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registro.css">
    <title>Perfil de Usuario</title>
    <script>
        function habilitarCampos() {
            // Habilitar los campos del formulario
            document.getElementById("username").disabled = false;
            document.getElementById("full-name").disabled = false;
            document.getElementById("birthdate").disabled = false;
            document.getElementById("profession").disabled = false;
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
            // Aquí puedes agregar lógica para guardar los cambios, por ejemplo, enviarlos a un servidor.
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
            <!-- Campo de correo electrónico (solo lectura) -->
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="user@example.com" disabled>
            </div>
            
            <!-- Campo de nombre de usuario -->
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" value="usuario123" disabled required>
            </div>
            
            <!-- Campo de nombre completo -->
            <div class="form-group">
                <label for="full-name">Nombre Completo</label>
                <input type="text" id="full-name" name="full-name" value="Juan Pérez" disabled required>
            </div>
            
            <!-- Campo de fecha de nacimiento -->
            <div class="form-group">
                <label for="birthdate">Fecha de Nacimiento</label>
                <input type="date" id="birthdate" name="birthdate" value="1990-05-15" disabled required>
            </div>
            
            <!-- Campo de profesión -->
            <div class="form-group">
                <label for="profession">Profesión</label>
                <input type="text" id="profession" name="profession" value="Desarrollador Web" disabled required>
            </div>
            
            <!-- Campo de foto de perfil -->
            <div class="form-group">
                <label for="profile-photo">Foto de Perfil</label>
                <input type="file" id="profile-photo" name="profile-photo" accept="image/*" disabled onchange="mostrarFoto(event)">
                <img src="media/user.png" alt="Foto de perfil" id="current-profile-photo" style="max-width: 150px; margin-top: 10px;">
            </div>

            <!-- Botón para habilitar los campos -->
            <button type="button" onclick="habilitarCampos()">Editar Perfil</button>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
