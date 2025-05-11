<?php
include 'back-end/conex.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener los grupos de chat del usuario
$query = "
    SELECT gc.id_grupo_chat, gc.nombre_grupo, gc.fecha_creacion
    FROM grupo_chat gc
    JOIN miembros_grupo_chat mgc ON gc.id_grupo_chat = mgc.id_grupo_chat
    WHERE mgc.id_usuario = ?
    ORDER BY gc.fecha_creacion DESC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result_grupos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/chat.css">
    <title>Chats Grupales</title>
    <style>
        #main-container {
            display: flex;
            height: calc(100vh - 60px); /* Ajustar según la altura de tu navbar */
            margin-left: 220px; /* Ancho del navleft */
        }
        
        #chat-list {
            width: 300px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            padding: 15px;
        }
        
        #chat-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 15px;
        }
        
        .chat-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .chat-item:hover {
            background-color: #f5f5f5;
        }
        
        .group-name {
            font-weight: bold;
            margin-left: 10px;
        }
        
        .group-icon {
            width: 40px;
            height: 40px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color:rgb(102, 102, 102);
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        #message-input {
            display: flex;
            gap: 10px;
        }
        
        #message-text {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .create-group-btn {
            display: block;
            margin: 15px 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            max-width: 70%;
        }
        
        .message.sent {
            background-color:rgb(67, 63, 66);
            align-self: flex-end;
            margin-left: auto;
        }
            
        .message.received {
            background-color:rgb(30, 7, 42);
            align-self: flex-start;
        }
        
        .message-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.8em;
            color: #777;
            margin-top: 5px;
        }
        
        .message-sender {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        #messages {
            display: flex;
            flex-direction: column;
        }
        
        #group-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        #group-info .group-icon {
            margin-right: 15px;
        }
        
        #group-members {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <div id="main-container">
        <div id="chat-list">
            <a href="crear_grupo_chat.php" class="create-group-btn">Crear nuevo grupo</a>
            
            <h3>Mis grupos de chat</h3>
            
            <?php if ($result_grupos->num_rows > 0): ?>
                <?php while ($row = $result_grupos->fetch_assoc()): ?>
                    <div class="chat-item" onclick="loadGroupChat(<?php echo $row['id_grupo_chat']; ?>, '<?php echo htmlspecialchars($row['nombre_grupo']); ?>')">
                        <div class="group-icon"><?php echo substr($row['nombre_grupo'], 0, 1); ?></div>
                        <div class="group-name"><?php echo htmlspecialchars($row['nombre_grupo']); ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tienes grupos de chat. ¡Crea uno nuevo!</p>
            <?php endif; ?>
        </div>

        <div id="chat-box">
            <div id="group-info">
                <h2 id="chat-title">Selecciona un grupo</h2>
                <div id="group-members"></div>
            </div>
            
            <div id="messages"></div>

            <div id="message-input" style="display: none;">
                <input type="text" id="message-text" placeholder="Escribe un mensaje...">
                <button onclick="sendGroupMessage()">Enviar</button>
            </div>
        </div>
    </div>

    <script>
        const currentUserId = <?php echo $id_usuario; ?>;
        let currentGroupId = null;
        
        function loadGroupChat(groupId, groupName) {
            currentGroupId = groupId;
            document.getElementById('chat-title').textContent = groupName;
            document.getElementById('message-input').style.display = 'flex';
            
            // Cargar miembros del grupo
            fetch(`get_group_members.php?group_id=${groupId}`)
                .then(response => response.json())
                .then(data => {
                    const membersList = data.map(member => member.nombre).join(', ');
                    document.getElementById('group-members').textContent = `Miembros: ${membersList}`;
                });
            
            // Cargar mensajes del grupo
            loadGroupMessages(groupId);
        }
        
        function loadGroupMessages(groupId) {
            fetch(`get_group_messages.php?group_id=${groupId}`)
                .then(response => response.json())
                .then(data => {
                    const messagesContainer = document.getElementById('messages');
                    messagesContainer.innerHTML = '';
                    
                    data.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `message ${msg.id_emisor == currentUserId ? 'sent' : 'received'}`;
                        
                        let messageContent = '';
                        if (msg.id_emisor != currentUserId) {
                            messageContent += `<div class="message-sender">${msg.nombre_emisor}</div>`;
                        }
                        
                        messageContent += `
                            <div class="message-content">${msg.contenido}</div>
                            <div class="message-info">
                                <span>${new Date(msg.fecha_envio).toLocaleString()}</span>
                            </div>
                        `;
                        
                        messageDiv.innerHTML = messageContent;
                        messagesContainer.appendChild(messageDiv);
                    });
                    
                    // Scroll to bottom
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                });
        }
        
        function sendGroupMessage() {
            const messageText = document.getElementById('message-text').value.trim();
            
            if (!messageText || !currentGroupId) return;
            
            const formData = new FormData();
            formData.append('group_id', currentGroupId);
            formData.append('message', messageText);
            
            fetch('send_group_message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('message-text').value = '';
                    loadGroupMessages(currentGroupId);
                } else {
                    alert('Error al enviar mensaje: ' + data.error);
                }
            });
        }
        
        // Event listener para enviar mensaje con tecla Enter
        document.getElementById('message-text').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendGroupMessage();
            }
        });
        
        // Verificar actualizaciones periódicamente
        function checkForNewMessages() {
            if (currentGroupId) {
                loadGroupMessages(currentGroupId);
            }
        }
        
        // Verificar cada 10 segundos
        setInterval(checkForNewMessages, 10000);
    </script>
</body>
</html>