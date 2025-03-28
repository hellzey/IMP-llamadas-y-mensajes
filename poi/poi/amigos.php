<?php

include 'back-end/conex.php';

session_start();
$id_usuario = $_SESSION['id_usuario']; 

$query = "
    SELECT u.id_usuario, u.nombre, u.foto_perfil, a.estado 
    FROM usuarios u
    JOIN Amistades a ON (a.id_remitente = u.id_usuario OR a.id_receptor = u.id_usuario)
    WHERE (a.id_remitente = ? OR a.id_receptor = ?)
    AND a.estado = 'aceptada'
    AND u.id_usuario != ?"; 

$stmt = $conexion->prepare($query);
$stmt->bind_param("iii", $id_usuario, $id_usuario, $id_usuario); 
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/amigos.css">
    <title>Amigos</title>
</head>
<body id="main-body"> 
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>
   
    <div class="container" id="main-container">
    <button id="add-button" onclick="openModal()">Agregar Amigo</button>
        <button id="requests-button" onclick="openRequestsModal()">Solicitudes de Amigos</button>

        <h1 id="main-heading">Amigos</h1>
        <ul id="friends-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto_perfil']); ?>" alt="Amigo" class="friend-image">
                    <?php echo htmlspecialchars($row['nombre']); ?> - <span class="online">En línea</span>
                </li>
            <?php } ?>
        </ul>
    </div>
<div id="add-friend-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Agregar Nuevo Amigo</h2>
        <input type="text" id="friend-username" placeholder="Username o Correo">
        <button onclick="addFriend()">Agregar</button>
    </div>
</div>




    <script src="js/agregar_amigos.js"></script>

    <div id="requests-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeRequestsModal()">&times;</span>
        <h2>Solicitudes de Amistad</h2>
        <ul id="requests-list">
         
        </ul>
    </div>
</div>

<script src="js/solicitudes_amigos.js"></script>




</body>
</html>
