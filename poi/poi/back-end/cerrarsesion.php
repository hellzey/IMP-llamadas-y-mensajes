<?php
session_start();  // Inicia la sesión
session_unset();  // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirigir al usuario a la página principal o inicio de sesión
header("Location: ../Iniciose.php");
exit;
?>
