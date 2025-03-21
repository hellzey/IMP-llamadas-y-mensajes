// Función para abrir el modal
function openModal() {
    document.getElementById("add-friend-modal").style.display = "block";
}

// Función para cerrar el modal
function closeModal() {
    document.getElementById("add-friend-modal").style.display = "none";
}

// Función para agregar un amigo
function addFriend() {
    var usernameOrEmail = document.getElementById("friend-username").value;
    
    if (usernameOrEmail === "") {
        alert("Por favor ingresa un username o correo.");
        return;
    }
    
    // Realiza la solicitud AJAX para agregar al amigo
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "back-end/agregar_amigo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText); // Muestra el resultado del proceso
            closeModal(); // Cierra el modal
        }
    };
    xhr.send("username_or_email=" + encodeURIComponent(usernameOrEmail)); // Envía el username o correo
}
