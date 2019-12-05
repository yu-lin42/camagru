function comment(id) {
    var input_field = document.getElementById('comment-input-' + id);

    var request = new XMLHttpRequest();
    request.addEventListener('load', () => {
        var commentText = (request.responseText);
        var commentDiv = document.getElementById('comment-box-' + id);
        var node = document.createElement("p");
        node.innerHTML = commentText;
        commentDiv.prepend(node);
    });
    request.open("POST", "/" + server_location + "/model/add_comments.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "comment_input=" + input_field.value +
        "&post_id=" + id
    );
}