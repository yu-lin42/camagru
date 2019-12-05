function like(id) {
    var request = new XMLHttpRequest();
    request.addEventListener('load', (res) => {
        if (request.status == 400){
            console.log(request.responseText);
        }
        if (request.status == 201){
            var like_count = document.getElementById('like_count-' + id);
            like_count.innerHTML = Number(like_count.innerHTML) + 1;
            console.log("Liked");
        }
        if (request.status == 204){
            var like_count = document.getElementById('like_count-' + id);
            like_count.innerHTML = Number(like_count.innerHTML) - 1;
            console.log("Disiked");
        }
    });
    request.open("POST", "/" + server_location + "/model/liked.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "post_id=" + id
    );
}