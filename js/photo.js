(function() {
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    var img;

    if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: true 
        }).then(function(stream) {
            video.srcObject = stream;
            video.play();
        });
    }

    function chooseimg(){
        var choose = document.querySelectorAll(".items");
    
        choose.forEach(function(element){
            element.addEventListener("click",function(){
            img = element;
            //console.log(img.src);
        });
    });}

    chooseimg();
    document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 400, 300);
        if (img){
            context.drawImage(img, 200, 20, 90, 90);
            var dataURL = canvas.toDataURL('image/png');
            document.getElementById("imgsrc").value = dataURL;
            console.log(document.getElementById("imgsrc").value);
        }
    });
})();