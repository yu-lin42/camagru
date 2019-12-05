<?php
    require('config/connect.php');
    session_start();
    require('view/clean.php');
    require('view/header.php');
?>

<head>
    <script src="/<?=$server_location?>/controller/update_user.js"></script>
    <link rel="stylesheet" href="/<?=$server_location?>/view/home.css">
</head>
<body>
    <main>
        <div class="contain_box">
            <input type="password" id="old_pwd" placeholder="Old Password"><br/>
            <input type="password" id="new_pwd" placeholder="New Password"><br/>
            <input type="password" id="confirm" placeholder="Reconfirm">
            <div>
                <button type="submit" onclick="update_password()">Update Password</button>
            </div>
        </div>
        <div id="message"></div>
    </main>
</body>
<?php
    require "footer.php";
?>