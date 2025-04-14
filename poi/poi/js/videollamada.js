let peer = null;
let currentCall = null;
let localStream = null;

document.addEventListener("DOMContentLoaded", () => {
    peer = new Peer(currentUserId.toString());

    peer.on('open', id => {
        console.log('Peer abierto con ID:', id);
    });

    // Responder llamada entrante
    peer.on('call', call => {
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                localStream = stream;
                document.getElementById('local-video').srcObject = stream;
                document.getElementById('video-container').style.display = 'block';

                call.answer(stream);

                call.on('stream', remoteStream => {
                    document.getElementById('remote-video').srcObject = remoteStream;
                });

                currentCall = call;
            })
            .catch(err => {
                console.error("Error accediendo a la cámara o micrófono:", err);
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
            document.getElementById('video-container').style.display = 'block';

            const call = peer.call(currentChatId.toString(), stream);
            call.on('stream', remoteStream => {
                document.getElementById('remote-video').srcObject = remoteStream;
            });

            currentCall = call;
        })
        .catch(err => {
            console.error("Error accediendo a la cámara o micrófono:", err);
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

    document.getElementById('video-container').style.display = 'none';
}
