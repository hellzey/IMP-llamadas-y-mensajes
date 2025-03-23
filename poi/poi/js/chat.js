let currentChatId = null; // ID del amigo con el que estamos chateando

function selectChat(friendId, friendName) {
    currentChatId = friendId;
    document.getElementById("chat-title").innerText = "Chat con " + friendName;
    document.getElementById("messages").innerHTML = ""; // Limpia mensajes anteriores

    // Cargar los mensajes anteriores
    fetch(`back-end/cargar_mensajes.php?friend_id=${friendId}`)
        .then(response => response.json())
        .then(messages => {
            messages.forEach(msg => {
                let messageDiv = document.createElement("div");
                messageDiv.classList.add("message", msg.tipo);
                messageDiv.textContent = msg.contenido;
                document.getElementById("messages").appendChild(messageDiv);
            });
        });
}

function sendMessage() {
    if (!currentChatId) {
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

    // Enviar mensaje al servidor
    fetch("back-end/enviar_mensaje.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `friend_id=${currentChatId}&message=${encodeURIComponent(messageText)}`
    });
}
