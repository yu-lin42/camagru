window.onload = function()
{
    var image_file = document.getElementById('fileToUpload');
    var canvas = document.getElementById('canvas');
    var pseudoCanvas = document.getElementById('pseudo-canvas');
    var previewElement = document.getElementById('preview');
    var stickerToggles = document.getElementsByClassName('sticker-toggle sticker');
    var context = canvas.getContext("2d");
    var addSticker = document.getElementById('add_sticker');
    var reset = document.getElementById('reset');
    var save = document.getElementById('save');
    
    addSticker.disabled = true;
    save.disabled = true;
    
    image_file.addEventListener("change", () => {
        var piece = null;
        for (var i=0; i <image_file.files.length; i++)
        {
            var file = image_file.files[i];
            if (file.type.match(/image\/*/))
            piece = file;
        }
        if (piece != null)
        {
            var pic = new Image();
            pic.onload = () =>{
                canvas.style.display = "flex";
                pseudoCanvas.style.display = "initial";
                
                canvas.height = pic.height;
                canvas.width = pic.width;
                pseudoCanvas.width = pic.width;
                pseudoCanvas.height = pic.height;
                context.drawImage(pic, 0, 0, canvas.offsetWidth, canvas.offsetHeight);
                canvas.style.position = 'absolute';
                addSticker.disabled = false;
                save.disabled=false;
            }
            pic.src = URL.createObjectURL(piece);
            console.log(pic.src);
        }
        
    });
    
    addSticker.addEventListener('click', function () {
        let stickersElement = document.getElementById('stickers');        
        stickersElement.style.display = 'initial';
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

    reset.addEventListener('click', () => {

        canvas.style.display = "none";
        pseudoCanvas.style.display = "none";
        addSticker.disabled = true;
        save.disabled = true;
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

        var pic_data = canvas.toDataURL('image/png');
        var request = new XMLHttpRequest();
        request.addEventListener('load', () => {
            console.log(request.responseText);
            location.reload();
        });
        request.open("POST", "/" + server_location + "/model/save_image.php");
        request.onreadystatechange = function (res) {
            if (request.readyState === request.DONE && request.status === 200){
                console.log(res);
            }
        };
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(
            "image=" + encodeURIComponent(pic_data.replace("data:image/png;base64,", ""))
            + "&stickers=" + stickersParam
        );
    });    
}