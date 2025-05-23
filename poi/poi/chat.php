<?php
include 'back-end/conex.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];

$query = "
    SELECT u.id_usuario, u.nombre, u.foto_perfil
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/chat.css">
    <title>Chat</title>
    <!-- PeerJS CDN -->
    <script src="https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
</head>
<body>

    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <div id="main-container">

        <div id="chat-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="chat-item" onclick="selectChat(<?php echo $row['id_usuario']; ?>, '<?php echo htmlspecialchars($row['nombre']); ?>', '<?php echo base64_encode($row['foto_perfil']); ?>')">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto_perfil']); ?>" alt="Foto de Perfil" class="chat-avatar">
                    <?php echo htmlspecialchars($row['nombre']); ?>
                </div>
            <?php } ?>
        </div>

        <div id="chat-box">
            <h2 id="chat-title">Selecciona un chat</h2>
            <div id="messages"></div>

            <div id="message-input">
                <input type="text" id="message-text" placeholder="Escribe un mensaje...">
                <button onclick="sendMessage()">▶</button>
                <button onclick="startVideoCall()" title="Iniciar videollamada">📹</button>
            </div>
        </div>
    </div>

    <!-- Modal Videollamada -->
    <div id="video-call-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeVideoCallModal()">&times;</span>
            <h3>Videollamada</h3>
            <video id="local-video" autoplay muted style="width: 300px;"></video>
            <video id="remote-video" autoplay style="width: 300px;"></video>
            <button onclick="endCall()">Finalizar llamada</button>
        </div>
    </div>

    <script>
        const currentUserId = <?php echo $id_usuario; ?>;
    </script>

    <script src="js/chat.js"></script>
    <script src="js/videollamada.js"></script>
</body>
</html>
