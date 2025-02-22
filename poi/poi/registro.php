<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registro.css">
    <title>Inicio de registro</title>
</head>
<body id="main-body"> 
    <?php include 'navtop.php'; ?>
   
    <div class="container" id="main-container">
    <!-- Sección izquierda con el logo -->
    <div class="logo-container">
        <img src="media/logo1.png" alt="Logo de la Página">
    </div>

    <!-- Sección derecha con el formulario -->
    <div class="form-container">
        <h1 id="main-heading">Registro</h1>
        <form id="login-form">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Fecha de Nacimiento</label>
                <input type="date" id="birthdate" name="birthdate" required>
            </div>
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="full-name">Nombre Completo</label>
                <input type="text" id="full-name" name="full-name" required>
            </div>
            <div class="form-group">
                <label for="profile-photo">Foto de Perfil</label>
                <input type="file" id="profile-photo" name="profile-photo" accept="image/*">
            </div>
            <div class="form-group">
                <label for="profession">Profesión</label>
                <input type="text" id="profession" name="profession" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</div>

</body>
</html>
