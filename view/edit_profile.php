<?php
    require('config/connect.php');
    require('view/clean.php');
    session_start();
    require('view/header.php');

    $sql = $conn->prepare("SELECT * FROM `users` WHERE `uid`= ?");
    $sql->execute(array($_SESSION['sess_uid']));
    if ($user = $sql->fetch(PDO::FETCH_ASSOC)){
?>

<head>
    <script src="/<?=$server_location?>/controller/update_user.js"></script>
    <link rel="stylesheet" href="/<?=$server_location?>/view/home.css">
</head>
<body>
    <main>
        <div>
            <div class="contain_box">
                <img src="/<?=$server_location?>/view/img/user_icon.png" width="20px"><label>  Current Username is: <?=$user['username']?></label>
                <div>
                    <input type="text" id="new_name" placeholder="New Username">
                    <button type="submit" onclick="update_username()">Change Username</button>
                </div>
                <br/>
                <img src="/<?=$server_location?>/view/img/email_icon.png" width="20px"><label>  Current Email is: <?=$user['email']?></label>
                <div>
                    <input type="text" id="new_mail" placeholder="New Email">
                    <button type="submit" onclick="update_email()">Change Email</button>
                </div>
            </div>
        </div>
        <div id="message"></div>
    </main>
</body>
<?php
    require "footer.php";
	}
	else {
		header("location: /$server_location/view/error.php?message=Access+denied");
	}
?>