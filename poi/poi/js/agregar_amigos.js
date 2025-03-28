
function openModal() {
    document.getElementById("add-friend-modal").style.display = "block";
}


function closeModal() {
    document.getElementById("add-friend-modal").style.display = "none";
}

function addFriend() {
    var usernameOrEmail = document.getElementById("friend-username").value;
    
    if (usernameOrEmail === "") {
        alert("Por favor ingresa un username o correo.");
        return;
    }
    

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "back-end/agregar_amigo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText); 
            closeModal(); 
        }
    };
    xhr.send("username_or_email=" + encodeURIComponent(usernameOrEmail)); 
}
