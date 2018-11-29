(function() {
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: true 
        }).then(function(stream) {
            video.srcObject = stream;
            video.play();
        });
    }
    document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 400, 300);
    });
})();