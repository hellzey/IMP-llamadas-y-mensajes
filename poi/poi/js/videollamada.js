let peer = null;
let currentCall = null;
let localStream = null;

document.addEventListener("DOMContentLoaded", () => {
    peer = new Peer(currentUserId.toString());

    peer.on('open', id => {
        console.log('Peer abierto con ID:', id);
    });

    peer.on('call', call => {
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                localStream = stream;
                document.getElementById('local-video').srcObject = stream;

                // Mostrar modal
                const modal = document.getElementById('video-call-modal');
                if (modal) modal.style.display = 'flex';

                call.answer(stream);
                call.on('stream', remoteStream => {
                    document.getElementById('remote-video').srcObject = remoteStream;
                });

                currentCall = call;
            })
            .catch(err => {
                console.error("Error al acceder a cámara o micrófono:", err);
            });
    });
});

function startVideoCall() {
    if (!currentChatId) {
        alert("Selecciona un usuario para llamar");
        return;
    }

    navigator.mediaDevices.getUserMedia({ video: true, audio: true })
        .then(stream => {
            localStream = stream;
            document.getElementById('local-video').srcObject = stream;

            const modal = document.getElementById('video-call-modal');
            if (modal) modal.style.display = 'flex';

            const call = peer.call(currentChatId.toString(), stream);
            call.on('stream', remoteStream => {
                document.getElementById('remote-video').srcObject = remoteStream;
            });

            currentCall = call;
        })
        .catch(err => {
            console.error("Error al acceder a cámara o micrófono:", err);
        });
}

function endCall() {
    if (currentCall) {
        currentCall.close();
        currentCall = null;
    }

    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
        localStream = null;
    }

    const modal = document.getElementById('video-call-modal');
    if (modal) modal.style.display = 'none';
}

function closeVideoCallModal() {
    endCall(); // También corta la llamada si se cierra el modal manualmente
}
