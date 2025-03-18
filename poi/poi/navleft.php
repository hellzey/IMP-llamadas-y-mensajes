<?php
// Comprobar si ya existe una sesión antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="css/navleft.css">

<?php if (isset($_SESSION['id_usuario'])): ?>
    <!-- Si el usuario está autenticado, mostrar el menú -->
    <nav id="nav-left">
        <div class="navleft-links">
            <a href="chat.php"><span>Chat</span><img src="media/chat.png" alt="Chat"></a>
            <a href="equipos.php"><span>Equipos</span><img src="media/red.png" alt="Equipos"></a>
            <a href="tareas.php"><span>Tareas</span><img src="media/tareas.png" alt="Tareas"></a>
            <a href="reward.php"><span>Reward</span><img src="media/reward.png" alt="Reward"></a>
            <a href="amigos.php"><span>Amigos</span><img src="media/amigo.png" alt="Amigos"></a>
        </div>

        <!-- Perfil del usuario con el mismo diseño de botón -->
        <a href="perfil.php" class="navleft-profile">
            <span><?php echo $_SESSION['username']; ?></span>
            <?php if (!empty($_SESSION['foto_perfil'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['foto_perfil']); ?>" alt="User Profile">
            <?php else: ?>
                <img src="media/user.png" alt="User Profile">
            <?php endif; ?>
        </a>
    </nav>
<?php else: ?>

<?php endif; ?>