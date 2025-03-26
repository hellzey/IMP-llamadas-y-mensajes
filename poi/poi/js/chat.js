let currentChatId = null; // ID del amigo con el que estamos chateando

// Función para seleccionar un chat
function selectChat(friendId, friendName) {
    currentChatId = friendId;
    document.getElementById("chat-title").innerText = "Chat con " + friendName;
    document.getElementById("messages").innerHTML = ""; // Limpiar mensajes anteriores

    // Cargar los mensajes anteriores
    loadMessages();

    // Iniciar actualización periódica cada 2 segundos
    setInterval(loadMessages, 2000);
}

// Función para cargar los mensajes de la base de datos
function loadMessages() {
    if (!currentChatId) return;

    fetch(`back-end/cargar_mensajes.php?friend_id=${currentChatId}`)
        .then(response => response.json())
        .then(messages => {
            const messagesContainer = document.getElementById("messages");
            messagesContainer.innerHTML = ""; // Limpiar mensajes previos

            messages.forEach(msg => {
                let messageDiv = document.createElement("div");
                messageDiv.classList.add("message", msg.tipo);
                messageDiv.textContent = msg.contenido;
                messagesContainer.appendChild(messageDiv);
            });

            messagesContainer.scrollTop = messagesContainer.scrollHeight; // Desplazar hacia abajo
        })
        .catch(error => console.error("Error al cargar los mensajes:", error));
}

// Función para enviar un mensaje
function sendMessage() {
    if (!currentChatId) {
        alert("Selecciona un chat primero.");
        return;
    }

    let messageText = document.getElementById("message-text").value;
    if (messageText.trim() === "") return;

    // Mostrar el mensaje en el chat
    let messageDiv = document.createElement("div");
    messageDiv.classList.add("message", "sent");
    messageDiv.textContent = messageText;
    document.getElementById("messages").appendChild(messageDiv);
    document.getElementById("message-text").value = ""; // Limpiar input

    // Enviar mensaje al servidor
    fetch("back-end/enviar_mensaje.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `friend_id=${currentChatId}&message=${encodeURIComponent(messageText)}`
    })
    .then(response => response.json()) // Asegurarnos de que recibimos un JSON como respuesta
    .then(data => {
        if (data.status === "success") {
            loadMessages(); // Recargar los mensajes después de enviar uno
        } else {
            console.error("Error al enviar el mensaje:", data.message);
        }
    })
    .catch(error => console.error("Error al enviar el mensaje:", error));
}
