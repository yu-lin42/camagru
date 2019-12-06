(function () {
    var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        pseudoCanvas = document.getElementById('pseudo-canvas'),
        context = canvas.getContext('2d'),
        addSticker = document.getElementById('add_sticker'),
        save = document.getElementById('save'),
        reset = document.getElementById('reset'),
        stickerToggles = document.getElementsByClassName('sticker-toggle'),
        previewElement = document.getElementById('preview');

    addSticker.disabled = true;
    save.disabled = true;
    reset.disabled = true;
    navigator.getMedia = navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia;


    navigator.getMedia(
        { video: true },
        function (stream) {
            video.srcObject = stream;
        },
        function (error) {
            console.log(error.message || error);
        }
    );

    document.getElementById('capture').addEventListener('click', function () {
        canvas.style.display = "flex";
        pseudoCanvas.style.display = "initial";

        pseudoCanvas.width = video.offsetWidth;
        pseudoCanvas.height = video.offsetHeight;
        canvas.width = video.offsetWidth;
        canvas.height = video.offsetHeight;
        context.drawImage(video, 0, 0, video.offsetWidth, video.offsetHeight);
        canvas.style.position = 'absolute';
        addSticker.disabled = false;
        save.disabled = false;
        reset.disabled = false;
        //manipulate canvas here
    });

    addSticker.addEventListener('click', function () {
        let stickersElement = document.getElementById('stickers');        
        stickersElement.style.display = 'initial';
    });

    reset.addEventListener('click', () => {
        let stickersElement = document.getElementById('stickers'); 
        stickersElement.style.display = 'none';
        canvas.style.display = "none";
        pseudoCanvas.style.display = "none";
        addSticker.disabled = true;
        save.disabled = true;
        reset.disabled = true;
        var stickersDisplayed = document.getElementsByClassName('sticker-preview');
        while(stickersDisplayed.length > 0){
            stickersDisplayed[0].parentNode.removeChild(stickersDisplayed[0]);
        }
        context.clearRect(0, 0, canvas.width, canvas.height);
    });

    save.addEventListener('click', () => {
        var stickers = [];
        var stickerPreviews = document.getElementsByClassName('sticker-preview');

        for (let stickerPreview of stickerPreviews) {
            stickers.push(stickerPreview.src);
        }

        var stickersParam = stickers.join(',');

        var photo_data = canvas.toDataURL('image/png');
        var request = new XMLHttpRequest();
        request.addEventListener('load', () => {
            console.log(request.responseText);
            location.reload();
        });

        request.open("POST", "/" + server_location + "/model/save_image.php");

        request.onreadystatechange = function (res) {
            if (request.readyState === request.DONE && request.status === 200) {
                console.log(res);
            }
        };

        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(
            "image=" + encodeURIComponent(photo_data.replace("data:image/png;base64,", ""))
            + "&stickers=" + stickersParam
        );
    });

    for (let stickerToggle of stickerToggles) {
        stickerToggle.onclick = () => { onToggleSticker(stickerToggle); }
    }

    function onToggleSticker(sticker) {
        const stickerPreviews = document.getElementsByClassName('sticker-preview');

        let create = true;

        for (let stickerPreview of stickerPreviews) {
            if (stickerPreview.src === sticker.src) {
                stickerPreview.remove();
                create = false;
                break;
            }
        }

        if (create) {
            const stickerPreviewElement = document.createElement('img');
            stickerPreviewElement.setAttribute('class', 'sticker-preview sticker');
            stickerPreviewElement.setAttribute('src', sticker.src);
            previewElement.append(stickerPreviewElement);
        }
    }

})();