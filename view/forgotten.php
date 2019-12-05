<?php
    require('config/connect.php');
    require('view/clean.php');
?>
<head>
    <link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
</head>
<body>
    <div class="container">
        <div>
            <a href="/<?=$server_location?>/index.php"><img src="/<?=$server_location?>/view/img/camagru_logo_lite.png" alt="logo" width=250px></a>
        </div>
        <div align="center">
            <h3>Verify your identity using your Email Address.</h3>
        </div>
        <div class="form">
            <form class="forgotbox">
                <div class="forgot_email">
                    <input id="email" type="text" name="mail" placeholder="Email">
                </div>
                <div class="forgotbox_btn">
                    <input id="reset" type="button" value="Verify"/>
                    <input id="cancel" type="button" value="Cancel"/>
                </div>
            </form>
        </div>
        <div align=center id="error_msg"></div>
    </div>
    <script>
        var email = document.getElementById("email");
        var reset = document.getElementById("reset");
        var cancel = document.getElementById("cancel");

        reset.addEventListener("click", () => {
            var request = new XMLHttpRequest();
            var msg = document.getElementById("error_msg");
            request.addEventListener("load", () => {
                if (request.status == 400){
                    msg.innerHTML = "The email address entered is not registered, please sign up.";
                }
                if (request.status == 200) {
                    msg.innerHTML = "The link to rest your password has been sent to your email.";
                }
            });
            request.open("POST", "/<?=$server_location?>/model/forgot_password.php");
            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            request.send(
                "mail=" + email.value
            );
        });

        cancel.addEventListener("click", () => {
            location.replace("/<?=$server_location?>/view/login.php");
        });
    </script>
</body>
<?php
    require "footer.php"
?>