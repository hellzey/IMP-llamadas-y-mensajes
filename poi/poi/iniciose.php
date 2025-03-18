<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/iniciose.css">
    <title>Inicio de Sesion</title>
</head>
<body id="main-body"> 
    <?php include 'navtop.php'; ?>
   
    <div class="container" id="main-container">
        <h1 id="main-heading">Iniciar Sesión</h1>
        
        <form id="login-form" method="POST" action="./back-end/login.php">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
