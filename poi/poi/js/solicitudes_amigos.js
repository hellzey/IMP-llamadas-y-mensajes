
function openRequestsModal() {
    document.getElementById("requests-modal").style.display = "block";
    loadFriendRequests(); 
}


function closeRequestsModal() {
    document.getElementById("requests-modal").style.display = "none";
}


function loadFriendRequests() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "back-end/obtener_solicitudes.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("requests-list").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function manageFriendRequest(id_amistad, action) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "back-end/gestionar_solicitud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            loadFriendRequests(); 
        }
    };

    xhr.send("id_amistad=" + encodeURIComponent(id_amistad) + "&action=" + encodeURIComponent(action));
}
