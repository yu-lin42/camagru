function reset_password() {
    var email = document.getElementById('email');
    var new_pwd = document.getElementById('new_pwd');
    var renew_pwd = document.getElementById('renewpwd');
    var token = document.getElementById('token');
    var msg = document.getElementById("error_msg");
    
    var request = new XMLHttpRequest();
    request.addEventListener("load", () => {
        if (request.status == 400){
            msg.innerHTML = "Password could not be resetted";
        }
        if (request.status == 200) {
            msg.innerHTML = "Password has been reset";
        }
    });
    if (new_pwd.value === renew_pwd) {
        msg.innerHTML = "Passwords do not match";
        return;
    }
    request.open("POST", "/" + server_location + "/model/renew_password.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "mail=" + email.value +
        "&newpwd=" + new_pwd.value +
        "&token=" + token.value
    );
};

function update_username() {
    var new_name = document.getElementById("new_name");

    var request = new XMLHttpRequest();
    request.addEventListener("load", () => {
        var msg = document.getElementById("message");
        if (request.status == 400) {
            // console.log(request.responseText);
            var msgs = request.responseText.split(":");

            msg.innerHTML = "";
            for (var i = 0; i < msgs.length; i++)
                msg.innerHTML += msgs[i] + "<br/>";
        }
        if (request.status == 200) {
            console.log("Username changed to " + new_name.value);
            location.reload();
        }
    });
    request.open("POST", "/" + server_location + "/model/change_username.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "new_name=" + encodeURIComponent(new_name.value)
    );
};

function update_email() {
    var new_mail = document.getElementById("new_mail");

    var request = new XMLHttpRequest();
    request.addEventListener("load", () => {
        var msg = document.getElementById("message");
        if (request.status == 400) {
            // console.log(request.responseText);
            var msgs = request.responseText.split(":");
            msg.innerHTML = "";
            for (var i = 0; i <msgs.length; i++)
                msg.innerHTML += msgs[i] + "<br/>";
        }
        if (request.status == 200) {
            console.log("Email changed to " + new_mail.value);
            location.reload();
        }
    });
    request.open("POST", "/" + server_location + "/model/change_email.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "new_mail=" + new_mail.value
    );
};

function update_password() {
    var old_pwd = document.getElementById("old_pwd");
    var new_pwd = document.getElementById("new_pwd");

    var request = new XMLHttpRequest();
    request.addEventListener("load", () => {
        var msg = document.getElementById("message");
        if (request.status == 400) {
            console.log(request.responseText);
            msg.innerHTML = "Please try again";
        }
        if (request.status == 200) {
            console.log("Password updated");
        }
    });
    request.open("POST", "/" + server_location + "/model/change_password.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(
        "old_pwd=" + old_pwd.value +
        "&new_pwd=" + new_pwd.value
    );
};