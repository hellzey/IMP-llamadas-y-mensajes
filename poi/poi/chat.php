<?php
include 'back-end/conex.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];

$query = "
    SELECT u.id_usuario, u.nombre 
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
</head>
<body>

    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <div id="main-container">

  
        <div id="chat-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="chat-item" onclick="selectChat(<?php echo $row['id_usuario']; ?>, '<?php echo htmlspecialchars($row['nombre']); ?>')">
                    <?php echo htmlspecialchars($row['nombre']); ?>
                </div>
            <?php } ?>
        </div>


        <div id="chat-box">
            <h2 id="chat-title">Selecciona un chat</h2>
            <div id="messages">
            </div>
            <div id="message-input">
                <input type="text" id="message-text" placeholder="Escribe un mensaje...">
                <button onclick="sendMessage()">▶</button>
            </div>
        </div>

    </div>

    <script src="js/chat.js"></script>

</body>
</html>
