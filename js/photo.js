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

    var img = new Image();
    img.src = "http://localhost:8080/Camagru_v2/img/1.png";
    document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 400, 300);
        context.drawImage(img, 200, 20, 90, 90);
    });
})();