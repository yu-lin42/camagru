window.onload = function (){
    
    var check = document.getElementById("notify");
    check.addEventListener("click", () => {
        if (check.checked === true)
            var notifi = 1;
        else
            notifi = 0;
        console.log(notifi);
        var request = new XMLHttpRequest();
        request.open("POST", "/" + server_location + "/model/notification.php");
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send("notifi=" + notifi);
    });
}