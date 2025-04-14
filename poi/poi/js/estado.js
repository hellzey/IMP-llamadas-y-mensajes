function actualizarEstadoAmigos() {
    fetch('back-end/estado_amigos.php')
        .then(response => response.json())
        .then(data => {
            const ahora = Date.now() / 1000; // tiempo en segundos
            data.forEach(amigo => {
                const li = document.querySelector(`li[data-id="${amigo.id_usuario}"]`);
                if (li) {
                    const span = li.querySelector('.estado-usuario');
                    const ultima = Date.parse(amigo.ultima_actividad) / 1000;
                    const enLinea = (ahora - ultima <= 120);
                    span.textContent = enLinea ? 'En línea' : 'Offline';
                    span.className = `estado-usuario ${enLinea ? 'online' : 'offline'}`;
                }
            });
        })
        .catch(error => console.error('Error actualizando estados:', error));
}

setInterval(actualizarEstadoAmigos, 10000); // cada 10 segundos
window.addEventListener('DOMContentLoaded', actualizarEstadoAmigos);
