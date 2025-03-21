// Función para abrir el modal de solicitudes de amistad
function openRequestsModal() {
    document.getElementById("requests-modal").style.display = "flex";
}

// Función para cerrar el modal de solicitudes de amistad
function closeRequestsModal() {
    document.getElementById("requests-modal").style.display = "none";
}

// Simulación de solicitudes de amistad
const friendRequests = [
    { name: "Juan Pérez" },
    { name: "María González" },
    { name: "Carlos López" }
];

// Cargar las solicitudes de amistad en el modal
document.addEventListener("DOMContentLoaded", function () {
    let requestsList = document.getElementById("requests-list");
    friendRequests.forEach(request => {
        let li = document.createElement("li");
        li.innerHTML = `
            <img src="media/user.png" alt="${request.name}" class="friend-image">
            ${request.name} 
            <button onclick="acceptRequest(this)">Aceptar</button>
            <button onclick="rejectRequest(this)">Rechazar</button>
        `;
        requestsList.appendChild(li);
    });
});

// Función para aceptar una solicitud
function acceptRequest(button) {
    let li = button.parentElement;
    document.getElementById("friends-list").appendChild(li); // Lo agrega a la lista de amigos
    li.removeChild(button.nextSibling); // Remueve el botón de rechazar
    li.removeChild(button); // Remueve el botón de aceptar
}

// Función para rechazar una solicitud
function rejectRequest(button) {
    let li = button.parentElement;
    li.remove(); // Borra la solicitud
}
