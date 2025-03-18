

<link rel="stylesheet" href="css/navtop.css">
<nav id="nav-top">
    <div class="navtop-logo">
        <a id="navtop-logo" href="Principal.php">
            <img src="media/logo1.png" alt="Logo" width="50">
        </a>
    </div>
    
    <div class="navtop-links">
    <div class="navtop-links">
    <?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>
    <?php if (isset($_SESSION['id_usuario'])): ?>
        <a href="back-end/cerrarsesion.php">Cerrar sesión</a>
    <?php else: ?>
        <a href="Iniciose.php">Iniciar sesión</a>
        <a href="Registro.php">Registro</a>
    <?php endif; ?>
</div>

    </div>
</nav>
