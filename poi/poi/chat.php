<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/chat.css">
    <title>Chat</title>
</head>
<body>

    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>

    <!-- Contenedor principal -->
    <div id="main-container">

        <!-- Lista de chats -->
        <div id="chat-list">
            <div class="chat-item" onclick="selectChat('Profe equis')">Profe equis</div>
            <div class="chat-item" onclick="selectChat('Jose')">Jose</div>
            <div class="chat-item" onclick="selectChat('Manuel')">Manuel</div>
            <div class="chat-item" onclick="selectChat('Dario')">Dario</div>
        </div>

        <!-- Contenedor del chat -->
        <div id="chat-box">
            <h2 id="chat-title">Selecciona un chat</h2>
            <div id="messages">
                <!-- Mensajes se actualizarán dinámicamente aquí -->
            </div>

            <!-- Input para escribir mensajes -->
            <div id="message-input">
                <input type="text" id="message-text" placeholder="Escribe un mensaje...">
                <button onclick="sendMessage()">▶</button>
            </div>
        </div>

    </div>

    <script>
        let currentChat = "";

        function selectChat(user) {
            currentChat = user;
            document.getElementById("chat-title").innerText = "Chat con " + user;
            document.getElementById("messages").innerHTML = ""; // Limpia mensajes anteriores
        }

        function sendMessage() {
            if (!currentChat) {
                alert("Selecciona un chat primero.");
                return;
            }

            let messageText = document.getElementById("message-text").value;
            if (messageText.trim() === "") return;

            let messageDiv = document.createElement("div");
            messageDiv.classList.add("message", "sent");
            messageDiv.textContent = messageText;

            document.getElementById("messages").appendChild(messageDiv);
            document.getElementById("message-text").value = "";
        }
    </script>

</body>
</html>
