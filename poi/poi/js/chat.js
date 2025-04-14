let currentChatId = null; 


function selectChat(friendId, friendName) {
    currentChatId = friendId;
    document.getElementById("chat-title").innerText = "Chat con " + friendName;
    document.getElementById("messages").innerHTML = ""; // Limpiar mensajes anteriores

 
    loadMessages();


    setInterval(loadMessages, 2000);
}


function loadMessages() {
    if (!currentChatId) return;

    fetch(`back-end/cargar_mensajes.php?friend_id=${currentChatId}`)
        .then(response => response.json())
        .then(messages => {
            const messagesContainer = document.getElementById("messages");
            messagesContainer.innerHTML = ""; 

            messages.forEach(msg => {
                let messageDiv = document.createElement("div");
                messageDiv.classList.add("message", msg.tipo);
                messageDiv.textContent = msg.contenido;
                messagesContainer.appendChild(messageDiv);
            });

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => console.error("Error al cargar los mensajes:", error));
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


    fetch("back-end/enviar_mensaje.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `friend_id=${currentChatId}&message=${encodeURIComponent(messageText)}`
    })
    .then(response => response.json()) 
    .then(data => {
        if (data.status === "success") {
            loadMessages(); 
        } else {
            console.error("Error al enviar el mensaje:", data.message);
        }
    })
    .catch(error => console.error("Error al enviar el mensaje:", error));
}
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("message-text");
    input.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            sendMessage();
        }
    });
});