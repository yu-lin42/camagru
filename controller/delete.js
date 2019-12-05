function deletePost(id) {
    var request = new XMLHttpRequest();
    request.addEventListener('load', () => {
        if (request.status == 400) {
            console.log(request.responseText);
        }
        if (request.status == 200) {
            document.getElementById("post-" + id).style.display = "none";
            console.log(request.responseText);
        }
    });
    request.open("POST", "/" + server_location + "/model/delete_post.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "post_id=" + id
    );
}